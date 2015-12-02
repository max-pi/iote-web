package com.iote.api;

import lombok.Data;

import java.util.ArrayList;
import lombok.Builder;

@Data
@Builder
public class User 
{
    private final String  _id;
    private final String twilioId;

    private final String lastName;
    private final String firstName;

    private final String email;
    private final String phone;

    private final ArrayList<Beacon> beacons;

}
