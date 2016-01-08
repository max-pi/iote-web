package com.iote.web.core;

import com.iote.web.api.Beacon;
import lombok.Data;

import java.security.Principal;
import java.util.List;
import lombok.Builder;

@Data
@Builder
public class User implements Principal {
    private final String  _id;

    private final String name;

    private final String password;
    private final List<String> emails;
    private final List<String> phones;
    private final List<Beacon> beacons;

    private final Metadata metadata;

    class Metadata {
        private String firstName;
        private String lastName;
    }
}
