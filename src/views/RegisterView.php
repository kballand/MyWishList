<?php

namespace MyWishList\views;


class RegisterView implements IView {
    public function render() {
        return
<<< END
<div id="registration">
    <h2>Register</h2>
    <form id="registerForm" action="/index.php">
        <div class="registerPart">
            <label for="password">Enter your first name</label>
            <input type="text" name="firstName" id="registerFirstName" placeholder="First Name" required>
            <label for="password">Enter your last name</label>
            <input type="text" name="lastName" id="registerLastName" placeholder="Last Name" required>
        </div>
        <div class="registerPart">
            <label for="username">Enter your username</label>
            <input type="text" name="username" id="registerUsername" placeholder="Username" required>
            <label for="email">Enter your email</label>
            <input type="email" name="email" id="registerEmail" placeholder="Email" required>
        </div>
        <div class="registerPart">
            <label for="password">Enter your password</label>
            <input type="password" name="password" id="registerPassword" placeholder="Password" required>
            <label for="password">Enter the same password</label>
            <input type="password" id="registerPasswordChecker" placeholder="Password" required>
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
</div>
END;
    }
}