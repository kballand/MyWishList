<?php

namespace MyWishList\views;


class RegisterView implements IView
{
    public function render()
    {
        return
            <<< END
<section class="basicForm">
    <form id="registerForm">
        <div class="registerPart">
            <label for="password">Enter your first name</label>
            <input type="text" name="firstName" id="registerFirstName" placeholder="First Name" required>
            <div class="errorContainer">
                <label class="error" id="firstNameError" for="firstName">This field can't be empty</label>
            </div>
            <label for="password">Enter your last name</label>
            <input type="text" name="lastName" id="registerLastName" placeholder="Last Name" required>
            <div class="errorContainer">
                <label class="error" id="lastNameError" for="lastName">This field can't be empty</label>
            </div>
        </div>
        <div class="registerPart">
            <label for="username">Enter your username</label>
            <input type="text" name="username" id="registerUsername" placeholder="Username" required>
            <div class="errorContainer">
                <label class="error" id="usernameLengthError" for="username">Your username must contain at least 5 characters</label>
                <label class="error" id="usernameTakenError" for="username">Username already taken</label>
            </div>
            <label for="email">Enter your email</label>
            <input type="email" name="email" id="registerEmail" placeholder="Email" required>
            <div class="errorContainer">
                <label class="error" id="emailError" for="email">Incorrect format of email</label>
            </div>
        </div>
        <div class="registerPart">
            <label for="password">Enter your password</label>
            <input type="password" name="password" id="registerPassword" placeholder="Password" required>
            <div class="errorContainer">
                <label class="error" id="passwordError" for="password">Your password must contain at least 5 characters</label>
            </div>
            <label for="password">Enter the same password</label>
            <input type="password" id="registerPasswordChecker" placeholder="Password" required>
            <div class="errorContainer">
                <label class="error" id="passwordCopyError" for="password">This password is different from the previous one</label>
            </div>
        </div>
        <div id="registerButtons">
            <button type="button" id="registerPreviousStep">Previous</button>
            <button type="button" id="registerNextStep">Next</button>
        </div>
        <div id="registerSteps">
            <span class="registerStep"></span>
            <span class="registerStep"></span>
            <span class="registerStep"></span>
        </div>
    </form>
</section>
END;
    }

    public function getRequiredCSS()
    {
        return ['/css/form.css', '/css/registration.css'];
    }

    public function getRequiredScripts()
    {
        return [];
    }
}