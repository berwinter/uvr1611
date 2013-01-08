//
//  logger.h
//  uvr1611
//
//  Created by Bertram Winter on 26.12.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#ifndef uvr1611_logger_h
#define uvr1611_logger_h

void initSyslog(void);
void printSyslog(const char * fmt, ...);
void closeSyslog(void);

#endif
