package com.iote.api;

import lombok.Data;

import java.util.List;

import lombok.Builder;

@Data
@Builder
public class User {
    private final String  _id;

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
