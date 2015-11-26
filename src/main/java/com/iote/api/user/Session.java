package com.iote.api.user;

import com.iote.api.User;
import lombok.Data;

@Data
public class Session {
    private final User user;
    private final String name;
}
