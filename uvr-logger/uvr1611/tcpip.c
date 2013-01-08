//
//  tcpip.c
//  uvr1611
//
//  Created by Bertram Winter on 18.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <sys/socket.h>
#include <netdb.h>
#include <netinet/in.h>

#include "tcpip.h"
#include "helper.h"
#include "logger.h"

static int sock;

void initialiseConnection(const char* hostname, const short port)
{
    sock = socket(PF_INET, SOCK_STREAM, 0);
    if(!sock)
    {
        printSyslog("Could not create socket");
        exitError();
    }
    struct hostent* hostinfo = gethostbyname(hostname);
    struct sockaddr_in destAddr;
    destAddr.sin_family = AF_INET;
    destAddr.sin_addr = *(struct in_addr*)*hostinfo->h_addr_list;
    destAddr.sin_port = htons(port);
    if(connect(sock, (const struct sockaddr *)&destAddr, sizeof(destAddr)))
    {
        printSyslog("Could not connect");
        exitError();
    }
    printSyslog("Connection established");
}

int queryCommand(uint8_t * sendBuf, int sendLength, uint8_t * recvBuf, int recvLength)
{
    
    if(send(sock, sendBuf, sendLength, 0) == sendLength)
    {
        if(recv(sock, recvBuf, recvLength, 0) == recvLength)
        {
            return 0;
        }
    } 
    
    printSyslog("Error during command query");
           
    return -1;
}

void closeConnection(void){
    close(sock);  
    printSyslog("Connection closed");
}
