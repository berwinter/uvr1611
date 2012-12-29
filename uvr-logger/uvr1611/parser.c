//
//  parser.c
//  tt
//
//  Created by Bertram Winter on 03.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>

#include "parser.h"

int parse_address(uint8_t * address)
{
    return (address[0] | address[1]<<8 | address[2]<<16);
}

void fix_address(int * address)
{
    *address = (*address & LOWADDRESS_MASK) | ((*address & HIGHADDRESS_MASK) >> 1);
}

void fill_address(uint8_t * buffer, int address)
{
    address = (address & LOWADDRESS_MASK) | ((address & UNFIX_HIGHADDRESS) << 1);
    
    buffer[0] = (uint8_t)(address & 0xFF);
    buffer[1] = (uint8_t)((address & 0xFF00) >> 8);
    buffer[2] = (uint8_t)((address & 0xFF0000) >> 16);
}

uint32_t parse_timestamp(uint8_t ** data)
{
    uint32_t timestamp = ((*data)[0] | (*data)[1]<<8 | (*data)[2] << 16);
    *data += 3;
    return timestamp;
}

analog_t parse_analog(uint8_t ** data)
{
    analog_t analog;
    short value = ((*data)[0] | ((*data)[1] & 0xF) << 8);
    
    if((*data)[1] & SENS_SIGN)
    {
        //printf("negvalue: %x",value);
        value |= 0xF000;
    }
    /*
    switch ((((*data)[1]) >> 4) & 0x7) {
        case SENS_TEMP:
            analog.type = TEMP;
            analog.value = (float)value / 10;
            break;
        case SENS_STREAM:
            analog.type = STREAM;
            analog.value = (float)value * 4;
            break;
        case SENS_RAD:
            analog.type = RADIATION;
            analog.value = (float)value;
            break;
        case SENS_RAS:
            analog.type = ROOM;
            value &= RAS_VALUE;
            analog.value = (float)value / 10;
            break;
    }*/
    analog.type = TEMP;
    analog.value = (float)value / 10;
    *data += 2;
    return analog;
}

void parse_digital(uint8_t ** data, digital_t * digital)
{
    uint16_t value = (*data)[0] | ((*data)[1]<<8);
    for(int i=0; i<13;i++)
    {
        if(value & 0x1)
        {
            digital->active = 1; 
        }
        else
        {
            digital->active = 0; 
        }
        value>>=1;
        digital++;
    }
    *data += 2;
}

speed_t parse_speed(uint8_t ** data)
{
    speed_t speed;
    speed.active = ((**data) & 0x80)? 0: 1;
    speed.speed = (**data) & 0x1F;
    *data += 1;
    return speed;
}

datetime_t parse_time(uint8_t ** data)
{
    datetime_t date;
    
    date.second = (*data)[0];
    date.minute = (*data)[1];
    date.hour = (*data)[2];
    date.day = (*data)[3];
    date.month = (*data)[4];
    date.year = (*data)[5];
    *data += 6;
    return date;
    
}

void parse_heatmeter(uint8_t ** data, heat_t * heatmeters)
{
    long temp;
    heatmeters[0].active = ((*data)[0] & 0x01)? 1: 0;
    heatmeters[1].active = ((*data)[0] & 0x02)? 1: 0;
    
    temp = (*data)[1] | ((*data)[2] << 8) | ((*data)[3] << 16) | ((*data)[4] << 24);
    
    heatmeters[0].power = (float)temp / 2560;
    heatmeters[0].kWh = (double)((*data)[5] | ((*data)[6] << 8))/10 + ((*data)[7] | ((*data)[8] << 8))*1000;
    
    temp = (*data)[9] | ((*data)[10] << 8) | ((*data)[11] << 16) | ((*data)[12] << 24);
    
    heatmeters[1].power = (float)temp / 2560;
    heatmeters[1].kWh = (double)((*data)[13] | ((*data)[14] << 8))/10 + ((*data)[15] | ((*data)[16] << 8))*1000;
    
    *data += 17;
}
