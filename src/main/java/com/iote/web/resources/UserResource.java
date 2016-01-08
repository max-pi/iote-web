package com.iote.web.resources;

import com.iote.web.db.UserDao;
import com.mongodb.*;
import com.iote.web.core.User;
import com.google.gson.Gson;

import java.net.UnknownHostException;


import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import javax.ws.rs.FormParam;
import javax.ws.rs.POST;

import org.bson.types.ObjectId;


@Path("/user")
@Produces(MediaType.APPLICATION_JSON)
public class UserResource {

    private UserDao userDao;

    public UserResource(UserDao userDao) { // initializing user resource
        this.userDao = userDao;
    }

    @GET
    @Path("/")
    // Searches for users based on specified params
    public User getUser() throws UnknownHostException {
        return User.builder().build();
    }

    @GET
    @Path("/active")
    // Returns the active user if one exists by Basic Access Authentication
    public User getActive() throws UnknownHostException {
        return User.builder()._id("ok buddy this is ID").password("yes").build();
    }

    @POST
    @Path("/register")
    // Creates a new and unactivated user account
    public void postRegister(@FormParam("contact") String contact,
                             @FormParam("password") String password) throws UnknownHostException {
        /*
        String type = contactValidation(contact);
        switch (type) {
            case "email": {
                User user = User.builder().password(password).build();
                ObjectId id = javaToMongoId(user);
                Email attempt = new Email(contact, id);
                break;
            }
            case "phone": {
                User user = User.builder().password(password).build();
                ObjectId id = javaToMongoId(user);
                boolean exist = false;
                for (Phone phone : phoneList) {
                    if (phone.number.equals(contact)) {
                        exist = true;
                        phone.attemptedUsers.add(phone.new Attempt(id));
                        break;
                    }
                }
                if (!exist) {
                    Phone attempt = new Phone(contact, id);
                }
                break;
            }
            default: {
                //response for invalid contact
                break;
            }
        }
        */
    }

    @POST
    @Path("/verify")
    public void postVerify(@FormParam("number") String num,
                           @FormParam("key") String key) throws UnknownHostException {
        /*
        for (Phone phone : phoneList) {
            if (phone.number.equals(num)) {
                Phone.Attempt user = phone.verify(key);
                if (user != null) {
                    DBCollection coll = this.mongoDb.getCollection("users");
                    BasicDBObject query = new BasicDBObject();
                    query.put("_id", user.id);
                    DBObject dbObj = coll.findOne(query);
                }
                break;
            }
        }
        */
    }


    /**
     * Takes in a string and checks for possible email or phone patterns
     *
     * @param contact the string to be checked for
     * @return the type of contact as a string
     */
    private String contactValidation(String contact) {
        String regex = ("^[\\w!#$%&'*+/=?`{|}~^-]+(?:\\.[\\w!#$%&'*+/=?`{|}~^-]"
                + "+)*@(?:[a-zA-Z0-9-]+\\.)+[a-zA-Z]{2,6}$");
        Pattern pattern = Pattern.compile(regex);
        Matcher matcher = pattern.matcher(contact);
        regex = "[0-9]{8,10}";          //basic 8-10 digit number, subject to change
        pattern = Pattern.compile(regex);
        Matcher matcher2 = pattern.matcher(contact);
        if (matcher.matches()) {
            return "email";
        } else if (matcher2.matches()) {
            return "phone";
        } else {
            return "invalid contact";
        }
    }

    /**
     * Saves a user object into mongodb and returns its id
     *
     * @param user the user to be added
     * @return the ObjectId of the newly added user
     * @throws UnknownHostException
     */
    private ObjectId javaToMongoId(User user) throws UnknownHostException {
        Gson gson = new Gson();
        String json = gson.toJson(user);
        BasicDBObject object = new BasicDBObject("users", json);
        //DBCollection coll = this.userDao.getCollection("users");
        //coll.insert(object);
        ObjectId id = (ObjectId) object.get("_id");
        return id;
    }
}