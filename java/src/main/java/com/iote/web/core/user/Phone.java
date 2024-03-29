package com.iote.web.core.user;

import java.util.List;
import lombok.Data;
import org.bson.types.ObjectId;
import java.util.UUID;

@Data
public class Phone
{
    public final String number;
    
    private boolean confirmed;
    private ObjectId confirmedUser;
    public  List<Attempt> attemptedUsers;
    
    public Phone ()
    {
        this.number = "";
    }
    
    public Phone (String number, ObjectId id) {
        this.number = number;
        this.attemptedUsers.add(this.new Attempt(id));
    }

    public Attempt verify (String key) {
        for (Attempt user : attemptedUsers) {
            if (user.key.equals(key)) {
                confirmed = true;
                confirmedUser = user.id;
                return user;
            }
        }
        return null;
    }
    
    public class Attempt {
        public ObjectId id;
        String key;
        
        public Attempt (ObjectId id) {
            this.id = id;
            this.key = UUID.randomUUID().toString();
        }
    }
}
