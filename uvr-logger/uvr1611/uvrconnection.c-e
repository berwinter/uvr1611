//
//  connection.c
//  uvr1611
//
//  Created by Bertram Winter on 18.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <unistd.h>
#include <mysql.h>
#include <string.h>

#include "uvrconnection.h"
#include "tcpip.h"
#include "helper.h"
#include "parser.h"
#include "logger.h"


static uint8_t buffer[1024];


static int waitCycles = 4;

static int waitms(int time);

void check_mode(void) { 
    // Get device mode (expect 0xDC for CAN mode)
    uint8_t mode = 0;
    uint8_t cmd = GET_MODE; 
    
    initialiseConnection(hostname, tcpPort);
    queryCommand(&cmd, 1, &mode, 1);
    closeConnection();
    
    if(mode != CAN_MODE)
    {
        exitError();
    }
}

int check_header(int * startAddress, int * endAddress) {
    CanHeader * header = (CanHeader *)buffer; 
    int count = 0;
    // Get header data
    uint8_t cmd = GET_HEADER; 
    
    initialiseConnection(hostname, tcpPort);
    if(queryCommand(&cmd, 1, buffer, 14) == 0)
    {
        CanTrailer * trailer = (CanTrailer *)(buffer + HEADER_SIZE + header->numberOfFrames);
        
        if(trailer->checksum != checksum8(buffer, HEADER_SIZE + header->numberOfFrames + TRAILER_SIZE))
        {
            printSyslog("Checksum error");
            exitError();
        }
        
        *startAddress = parse_address(trailer->startAddress);
        *endAddress = parse_address(trailer->endAddress);
        
        if(*startAddress == EMPTY_BUFFER && *endAddress == EMPTY_BUFFER)
        {
            printSyslog("No new data");
            printf("No data!\n");
            exitError();
        }
        else
        {
            fix_address(startAddress);
            fix_address(endAddress);
            count = (*endAddress-*startAddress)/0x40 + 1;
        }
    }
    else
    {
        printSyslog("Could not get info");
        exitError();
    }
    closeConnection();
    return count;
}

void recv_data(int startAddress, int endAddress, int count)
{
    uint8_t sendBuf[6];
    sendBuf[0] = READ_DATA;
    sendBuf[4] = NUMBER_OF_SETS;
    CanData data;
    
    MYSQL *conn;
    char *server = "mybook.lan";
    char *user = "uvr1611";
    char *password = "uvr1611"; 
    char *database = "uvr1611";
    char query[1024];
    char blob[256];
    char date[20];
    char temp[3];
    conn = mysql_init(NULL);
    
    if (!mysql_real_connect(conn, server,
                            user, password, database, 0, NULL, 0)) {
        printSyslog("%s", mysql_error(conn));
        exitError();
    }
    
    uint8_t * pData;
    while (count > 0)
    {
        initialiseConnection(hostname, tcpPort);
        fill_address(&sendBuf[1], startAddress);
        sendBuf[5] = checksum8(sendBuf, 5);
        if(queryCommand(sendBuf, 6, buffer, 65) == 0)
        {
            printSyslog("Read from 0x%x",startAddress);
            closeConnection();
            
            if(checksum8(buffer, 64) == buffer[64])
            {
                
                pData = buffer;
                data.timestamp = parse_timestamp(&pData);
                for(int i=0; i<16;i++)
                {
                    data.analogs[i] = parse_analog(&pData);
                }
                
                parse_digital(&pData, data.digitals);
                
                for(int i=0; i<4;i++)
                {
                    data.speed[i] = parse_speed(&pData);
                }
                
                parse_heatmeter(&pData, data.heatmeters);
                
                data.time = parse_time(&pData);
                
                sprintf(date, DATE_FORMATTER,data.time.year , data.time.month, data.time.day, data.time.hour, data.time.minute, data.time.second);
                sprintf(query, INSERT_QUERY, date,
                        data.analogs[0].value, data.analogs[1].value, data.analogs[2].value, data.analogs[3].value,
                        data.analogs[4].value, data.analogs[5].value, data.analogs[6].value, data.analogs[7].value, 
                        data.analogs[8].value, data.analogs[9].value, data.analogs[10].value, data.analogs[11].value, 
                        data.analogs[12].value, data.analogs[13].value, data.analogs[14].value, data.analogs[15].value, 
                        data.digitals[0].active, data.digitals[1].active, data.digitals[2].active, data.digitals[3].active,
                        data.digitals[4].active, data.digitals[5].active, data.digitals[6].active, data.digitals[7].active,
                        data.digitals[8].active, data.digitals[9].active, data.digitals[10].active, data.digitals[11].active, 
                        data.digitals[12].active,
                        data.speed[0].speed, data.speed[1].speed, data.speed[2].speed, data.speed[3].speed,
                        data.heatmeters[0].power,
                        data.heatmeters[1].power);
                
            
                /* send SQL query */
                if (mysql_query(conn,query)) {
                    printSyslog("%s", mysql_error(conn));
                    exitError();
                }
                
                // save energy values
                sprintf(query, POWER_INSERT_QUERY, data.time.year , data.time.month, data.time.day,
                        data.heatmeters[0].kWh, data.heatmeters[1].kWh);
                
                if (mysql_query(conn,query)) {
                    printSyslog("%s", mysql_error(conn));
                    exitError();
                }
                
                strcpy(blob, "0x");
                
                for(int i=0; i<64;i++)
                {
                    sprintf(temp, "%02x", buffer[i]);
                    strcat(blob,temp);
                }
                
                
                // save binary buffer
                sprintf(query, INSERT_BINARY_QUERY, blob, date);
                
                if (mysql_query(conn,query)) {
                    printSyslog("%s", mysql_error(conn));
                    exitError();
                }
            }
            else
            {
                printSyslog("CRC error in dataset %d",count);
            }
            count--;
            startAddress += 0x40;
        }
        else
        {
            printSyslog( "Receive error in dataset %d", count);
            exitError();
        }
    }
    mysql_close(conn);
}

void recv_latest(void)
{
    uint8_t sendBuf[6];
    sendBuf[0] = GET_LATEST;
    sendBuf[1] = NUMBER_OF_SETS;
    CanData data;
    
    uint8_t * pData;
    initialiseConnection(hostname, tcpPort);
    do
    {
        if(queryCommand(sendBuf, 2, buffer, 57) == 0 && buffer[0] != 0xBA && buffer[0] != 0xAB)
        {
            if(checksum8(buffer, 56) == buffer[56])
            {
                pData = buffer+1;
                for(int i=0; i<16;i++)
                {
                    data.analogs[i] = parse_analog(&pData);
                }
                
                parse_digital(&pData, data.digitals);
                
                for(int i=0; i<4;i++)
                {
                    data.speed[i] = parse_speed(&pData);
                }
                
                parse_heatmeter(&pData, data.heatmeters);

                printf(JSON_STRING,
                       data.analogs[0].value, data.analogs[1].value, data.analogs[2].value, data.analogs[3].value,
                       data.analogs[4].value, data.analogs[5].value, data.analogs[6].value, data.analogs[7].value, 
                       data.analogs[8].value, data.analogs[9].value, data.analogs[10].value, data.analogs[11].value, 
                       data.analogs[12].value, data.analogs[13].value, data.analogs[14].value, data.analogs[15].value, 
                       data.digitals[0].active, data.digitals[1].active, data.digitals[2].active, data.digitals[3].active,
                       data.digitals[4].active, data.digitals[5].active, data.digitals[6].active, data.digitals[7].active,
                       data.digitals[8].active, data.digitals[9].active, data.digitals[10].active, data.digitals[11].active, 
                       data.digitals[12].active,
                       data.speed[0].speed, data.speed[1].speed, data.speed[2].speed, data.speed[3].speed,
                       data.heatmeters[0].power, data.heatmeters[0].kWh,
                       data.heatmeters[1].power, data.heatmeters[1].kWh);
            }
            
        }
    } while (buffer[0] == 0xBA && waitms(buffer[1]) > 0);
    closeConnection();
}

void reset_data(void)
{
    uint8_t cmd = END_READ; 
    uint8_t responce = 0;
    
    initialiseConnection(hostname, tcpPort);
    queryCommand(&cmd, 1, &responce, 1);
    
    if(cmd == END_READ && bReset == 1)
    {
        cmd = RESETDATA; 
        queryCommand(&cmd, 1, &responce, 1);
    }
    closeConnection();
}

static int waitms(int time)
{
    closeConnection();
    printSyslog("Go to sleep for %d ms", time);
    sleep(time);
    waitCycles--;
    initialiseConnection(hostname, tcpPort);
    return  waitCycles;
}
