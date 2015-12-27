package com.iote.api.user;

import lombok.Data;
import java.util.List;
import java.util.UUID;
import org.bson.types.ObjectId;


@Data
public class Email
{
    private final String email;
    private final ObjectId id;
    private final String key;
    
    private boolean confirmed;
    private String confirmedUser;
    private static List<Attempt> attemptedUsers;
    
    public Email (String email, ObjectId id)
    {
        this.email = email;
        this.id = id;
        this.key = UUID.randomUUID().toString();
    }

    class Attempt 
    {
        private String hi;
    }
}
