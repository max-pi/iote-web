package com.iote.api;

import lombok.Data;
import org.joda.time.DateTime;

@Data
public class Application {

    private final String name;

    private final String token;
    private final boolean active;
    private final String platform;

    private final DateTime timestamp;
}
