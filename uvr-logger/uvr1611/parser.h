//
//  parser.h
//  tt
//
//  Created by Bertram Winter on 03.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#ifndef uvr1611_parser_h
#define uvr1611_parser_h

#include "uvrconnection.h"

#define LOWADDRESS_MASK 0xFF
#define HIGHADDRESS_MASK 0xFFFE00
#define UNFIX_HIGHADDRESS 0xFFFF00
#define SENS_TEMP 0b010
#define SENS_RAD  0b110
#define SENS_STREAM  0b011
#define SENS_RAS  0b111
#define RAS_VALUE 0x1F
#define SENS_SIGN 0x80


void fix_address(int * address);
void fill_address(uint8_t * buffer, int address);

int parse_address(uint8_t * address);
uint32_t parse_timestamp(uint8_t ** data);
analog_t parse_analog(uint8_t ** data);
void parse_digital(uint8_t ** data, digital_t * digital);
void parse_heatmeter(uint8_t ** data, heat_t * heatmeters);
speed_t parse_speed(uint8_t ** data);
datetime_t parse_time(uint8_t ** data);


#endif
