package com.iote.api.user;

import lombok.Data;
import org.joda.time.DateTime;

@Data
public class Registration {

    private final String uuid;

    private final String ipAddress;
    private final String geoCoordinates;

    private final String platform;
    private final String userAgent;
    private final String contactType;

    private final DateTime timestamp;

    //what did the user use for registration (email or phone)

}
