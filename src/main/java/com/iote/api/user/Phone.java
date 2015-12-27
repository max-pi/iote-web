package com.iote.api.user;

import lombok.Data;
import org.bson.types.ObjectId;
import java.util.UUID;

@Data
public class Phone
{
    public final String number;
    private final ObjectId id;
    private final String key;
    
    private boolean confirmed;
    private String confirmedUser;
    private static String[][] attemptedUsers;
    
    public Phone (String number, ObjectId id)
    {
        this.number = number;
        this.id = id;
        this.key = UUID.randomUUID().toString();
    }
}
