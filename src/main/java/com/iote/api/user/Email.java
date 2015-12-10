package com.iote.api.user;

import lombok.Data;

import java.util.List;

@Data
public class Email {
    private final String _id;
    private final String email;
    private final boolean confirmed;
    private final String confirmedUser;
    private final List<Attempt> attemptedUsers;

    class Attempt {
        private String user;
        private String key;
    }
}