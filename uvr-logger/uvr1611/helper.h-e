//
//  helper.h
//  tt
//
//  Created by Bertram Winter on 03.11.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#ifndef uvr1611_helper_h
#define uvr1611_helper_h

#include <stdint.h>
#define ERROR -1
#define PID_FILE "/var/run/uvr1611/uvr1611-logger.pid"


extern int tcpPort;
extern int bActual;
extern int bReset;
extern int bVerbose;
extern char * hostname;

uint8_t checksum8(uint8_t * data, int length );
void parseOptions(int argc, char ** argv);
void exitError(void);
void createPid(void);


#endif
