
package com.iote.api;

import java.util.UUID;
import org.bson.types.ObjectId;


public class Emails 
{
    private final String email;
    private final ObjectId id;
    private final String key;
    
    private boolean confirmed;
    private String confirmedUser;
    private static String[][] attemptedUsers;
    
    public Emails (String email, ObjectId id)
    {
        this.email = email;
        this.id = id;
        this.key = UUID.randomUUID().toString();
    }
}
