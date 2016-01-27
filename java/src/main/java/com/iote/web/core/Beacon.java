package com.iote.web.core;

import lombok.Data;

@Data
public class Beacon {
    private final String  _id;
    private final boolean isOwned;
    private final boolean isLost;

    private final Metadata metadata;

    class Metadata {
        private String name;
    }
}
