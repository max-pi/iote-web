package com.iote.web.api.user;

import lombok.Data;
import java.util.List;
import java.util.UUID;
import org.bson.types.ObjectId;


@Data
public class Email {
    public final String email;
    
    private boolean confirmed;
    private ObjectId confirmedUser;
    public  List<Attempt> attemptedUsers;
    
    public Email ()
    {
        this.email = "";
    }
    
    public Email (String email, ObjectId id) {
        this.email = email;
        this.attemptedUsers.add(this.new Attempt(id));
    }
    
    public class Attempt {
        ObjectId id;
        String key;
        
        public Attempt (ObjectId id) {
            this.id = id;
            this.key = UUID.randomUUID().toString();
        }
    }
}
