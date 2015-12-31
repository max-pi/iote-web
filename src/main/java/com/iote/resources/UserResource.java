package com.iote.resources;

import com.mongodb.*;
import com.iote.api.User;
import com.iote.api.user.Email;
import com.iote.api.user.Phone;
import com.iote.api.user.Phone.Attempt;
import com.google.common.base.Optional;
import com.codahale.metrics.annotation.Timed;
import com.google.gson.Gson;
import java.net.UnknownHostException;
import java.util.List;


import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.MediaType;
import java.util.concurrent.atomic.AtomicLong;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import javax.ws.rs.FormParam;
import javax.ws.rs.POST;
import org.bson.types.ObjectId;


@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class UserResource
{
    private final String template;
    private final String defaultName;
    private final AtomicLong counter;
    private static List<Email> emailList;
    private static List<Phone> phoneList;

    public UserResource(String template, String defaultName)
    {
        this.template = template;
        this.defaultName = defaultName;
        this.counter = new AtomicLong();
        
    }
 

    @GET
    @Timed
    public User getUser(@QueryParam("name") Optional<String> name) throws UnknownHostException
    {
        DB db = connectDB();
        String temp = db.getCollectionNames().toString();
        return User.builder()._id(temp).build();
    }
    
    @Path("/register")
    @POST
    public void registration(@FormParam("email or phone") String contact,
                             @FormParam("password") String pw)
                                                  throws UnknownHostException
    {
        String type = contactValidation(contact);
        switch (type) {
            case "email":
            {
                User user = User.builder().password(pw).build();
                ObjectId id = javaToMongoId(user);
                Email attempt = new Email(contact, id);
                break;
            }
            case "phone":
            {
                User user = User.builder().password(pw).build();
                ObjectId id = javaToMongoId(user);
                boolean exist = false;
                for (Phone phone : phoneList) 
                {
                    if (phone.number.equals(contact)) 
                    {
                      exist = true;
                      phone.attemptedUsers.add(phone.new Attempt(id));
                      break;
                    }
                }
                if (!exist)
                {
                    Phone attempt = new Phone(contact, id);
                } 
                break;
            }
            default:
            {
                //response for invalid contact
                break;
            }
        }
    }
    
    @Path("/verify")
    @POST
    public void verification(@FormParam("number") String num,
                             @FormParam("key") String key)
                                                  throws UnknownHostException
    {
        for (Phone phone : phoneList) 
        {
            if (phone.number.equals(num)) 
            {
                Phone.Attempt user = phone.verify(key);
                if (user != null)
                {
                    DBCollection coll = connectDB().getCollection("users");
                    BasicDBObject query = new BasicDBObject();
                    query.put("_id", user.id);
                    DBObject dbObj = coll.findOne(query);
                }
                break;
            }
        }
    }
    
    private DB connectDB() throws UnknownHostException
    {
        MongoClient mongo = new MongoClient( "localhost" , 27017 );
        DB db = mongo.getDB("iote");
        return db;
    }
    
    /**
     * Takes in a string and checks for possible email or phone patterns
     * @param contact the string to be checked for
     * @return the type of contact as a string
     */
    private String contactValidation(String contact)
    {
        String regex = ("^[\\w!#$%&'*+/=?`{|}~^-]+(?:\\.[\\w!#$%&'*+/=?`{|}~^-]"
                        + "+)*@(?:[a-zA-Z0-9-]+\\.)+[a-zA-Z]{2,6}$");
        Pattern pattern = Pattern.compile(regex);
        Matcher matcher = pattern.matcher(contact);
        regex = "[0-9]{8,10}";          //basic 8-10 digit number, subject to change
        pattern = Pattern.compile(regex);
        Matcher matcher2 = pattern.matcher(contact);
        if (matcher.matches())
        {
            return "email";
        }
        else if (matcher2.matches())
        {
            return "phone"; 
        }
        else
        {
            return "invalid contact";
        }
    }
    
    /**
     * Saves a user object into mongodb and returns its id
     * @param user the user to be added
     * @return the ObjectId of the newly added user
     * @throws UnknownHostException 
     */
    private ObjectId javaToMongoId(User user) throws UnknownHostException
    {
        Gson gson = new Gson();
        String json = gson.toJson(user);
        BasicDBObject object = new BasicDBObject("users", json);
        DB db = connectDB();
        DBCollection coll = db.getCollection("users");
        coll.insert(object);
        ObjectId id = (ObjectId) object.get( "_id" );
        return id;
    }
}