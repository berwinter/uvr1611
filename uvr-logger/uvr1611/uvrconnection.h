//
//  connection.h
//  uvr1611
//
//  Created by Bertram Winter on 18.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#ifndef uvr1611_connection_h
#define uvr1611_connection_h

#define GET_HEADER 0xAA
#define GET_LATEST  0xAB
#define GET_MODE   0x81
#define READ_DATA  0xAC
#define END_READ   0xAD
#define RESETDATA 0xAF
#define CAN_MODE   0xDC

#define HEADER_SIZE 6
#define TRAILER_SIZE 6 // without checksum
#define EMPTY_BUFFER 0xFFFFFF
#define NUMBER_OF_SETS 1
#define MAX_LENGTH 524
#define DATE_FORMATTER "20%02d-%02d-%02d %02d:%02d:%02d"
#define INSERT_QUERY "REPLACE INTO data (timestamp, analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8, analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16, digital1, digital2, digital3, digital4, digital5, digital6, digital7, digital8, digital9, digital10, digital11, digital12, digital13, speed1, speed2, speed3, speed4, heatmeter1_power, heatmeter2_power) VALUES ('%s',%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%3.2f,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%.3f,%.3f)"
#define INSERT_BINARY_QUERY "UPDATE data SET bin=%s WHERE timestamp = '%s'"
#define POWER_INSERT_QUERY "REPLACE INTO energy (timestamp, energy1, energy2) VALUES ('20%02d-%02d-%02d',%.1f,%.1f)"
#define JSON_STRING "{\"analog1\":\"%.1f\",\"analog2\":\"%.1f\",\"analog3\":\"%.1f\",\"analog4\":\"%.1f\",\"analog5\":\"%.1f\",\"analog6\":\"%.1f\",\"analog7\":\"%.1f\",\"analog8\":\"%.1f\",\"analog9\":\"%.1f\",\"analog10\":\"%.1f\",\"analog11\":\"%.1f\",\"analog12\":\"%.1f\",\"analog13\":\"%.1f\",\"analog14\":\"%.1f\",\"analog15\":\"%.1f\",\"analog16\":\"%.1f\",\"digital1\":\"\\u%04d\",\"digital2\":\"\\u%04d\",\"digital3\":\"\\u%04d\",\"digital4\":\"\\u%04d\",\"digital5\":\"\\u%04d\",\"digital6\":\"\\u%04d\",\"digital7\":\"\\u%04d\",\"digital8\":\"\\u%04d\",\"digital9\":\"\\u%04d\",\"digital10\":\"\\u%04d\",\"digital11\":\"\\u%04d\",\"digital12\":\"\\u%04d\",\"digital13\":\"\\u%04d\",\"speed1\":\"%d\",\"speed2\":\"%d\",\"speed3\":\"%d\",\"speed4\":\"%d\",\"heatmeter1_power\":\"%.3f\",\"heatmeter1_energy\":\"%.1f\",\"heatmeter2_power\":\"%.3f\",\"heatmeter2_energy\":\"%.1f\"}"

typedef struct
{
    uint8_t type;
    uint8_t version;
    uint8_t timestamp[3];
    uint8_t numberOfFrames;
} CanHeader;


typedef struct
{
    uint8_t startAddress[3];
    uint8_t endAddress[3];
    uint8_t checksum;
} CanTrailer;

typedef enum {TEMP, ROOM, RADIATION, STREAM} analog_e;

typedef struct {
    analog_e type;
    float value;
} analog_t;

typedef struct {
    uint8_t active;
} digital_t;

typedef struct {
    uint8_t active;
    uint8_t speed;
} speed_t;

typedef struct {
    uint8_t active;
    float power;
    double kWh;
} heat_t;

typedef struct {
    uint8_t second;
    uint8_t minute;
    uint8_t hour;
    uint8_t day;
    uint8_t month;
    uint8_t year;
} datetime_t;

typedef struct
{
    uint32_t timestamp;
    analog_t analogs[16];
    digital_t digitals[13];
    speed_t  speed[4];
    heat_t  heatmeters[2];
    datetime_t time;
} CanData;

void check_mode(void);
void reset_data(void);
int check_header(int * startAddress, int * endAddress);
void recv_data(int startAddress, int endAddress, int count);
void recv_latest(void);

#endif
