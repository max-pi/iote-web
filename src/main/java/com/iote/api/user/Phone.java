package com.iote.api.user;

import java.util.List;
import lombok.Data;
import org.bson.types.ObjectId;
import java.util.UUID;

@Data
public class Phone
{
    public final String number;
    
    private boolean confirmed;
    private String confirmedUser;
    public  List<Attempt> attemptedUsers;
    
    public Phone ()
    {
        this.number = "";
    }
    
    public Phone (String number, ObjectId id)
    {
        this.number = number;
        this.attemptedUsers.add(this.new Attempt(id));
    }
    
    public class Attempt
    {
        ObjectId id;
        String key;
        
        public Attempt (ObjectId id)
        {
            this.id = id;
            this.key = UUID.randomUUID().toString();
        }
    }
    
    
}
