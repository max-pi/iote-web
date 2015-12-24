
package com.iote.api;

import org.bson.types.ObjectId;
import java.util.UUID;


public class Phones 
{
    private final String number;
    private final ObjectId id;
    private final String key;
    
    private boolean confirmed;
    private String confirmedUser;
    private static String[][] attemptedUsers;
    
    public Phones (String number, ObjectId id)
    {
        this.number = number;
        this.id = id;
        this.key = UUID.randomUUID().toString();
    }
}
