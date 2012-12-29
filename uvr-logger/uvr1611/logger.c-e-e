//
//  logger.c
//  uvr1611
//
//  Created by Bertram Winter on 26.12.12.
//  Copyright 2012 FH Joanneum. All rights reserved.
//

#include <stdio.h>
#include <syslog.h>
#include <stdarg.h>


#include "helper.h"
#include "logger.h"


void initSyslog(void)
{
    if(bVerbose)
    {
        openlog("uvr1611-logger", LOG_PID|LOG_CONS, LOG_USER);
    }
}

void printSyslog(const char * fmt, ...) 
{
    char buf[100];
    
    if(bVerbose)
    {
        va_list argptr;
        va_start(argptr,fmt);
        vsnprintf(buf,100, fmt, argptr);
        va_end(argptr);
        
        syslog(LOG_INFO,"%s",buf);
    }
}

void closeSyslog(void)
{
    if(bVerbose)
    {
        openlog("uvr1611-logger", LOG_PID|LOG_CONS, LOG_USER);
    }
}
