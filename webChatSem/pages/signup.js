function imageViewer(src = "") {
    return {
        imageUrl: src,
        fileChosen(event) {
            this.fileToDataUrl(event, (src) => (this.imageUrl = src));
        },
        fileToDataUrl(event, callback) {
            console.log("fileChange");
            if (!event.target.files.length) {
                callback(src);
                return;
            }

            let file = event.target.files[0],
                reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = (e) => callback(e.target.result);
        },
    };
}

function signUpData() {
    return {
        firstName: "",
        lastName: "",
        username: "",
        email: "",
        password: "",
        phone: "",

        usernameDebounceTimeout: null,
        usernameAvailable: false,
        formValid: false,

        errors: {
            username: "",
            email: "",
            firstName: "",
            lastName: "",
            password: "",
            phone: "",
        },

        submitForm() {
            this.validateFirstName();
            this.validateLastName();
            this.validateUsername() &&
                this.checkUsernameAvailability(this.username);
            this.validateEmail();
            this.validatePhone();
            this.validatePassword();

            // check errors
            if (Object.values(this.errors).some((error) => error)) {
                console.log("invalid form");
                return false;
            }
            return true;
        },
        requiredValidator(fieldName) {
            if (!this[fieldName].trim()) {
                this.errors[`${fieldName}`] = `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)
                    } is required`;
                return false;
            }
            return true;
        },
        phoneFormatValidator() { },
        validateFirstName() {
            this.errors.firstName = "";
            this.requiredValidator("firstName");
        },
        validateLastName() {
            this.errors.lastName = "";
            this.requiredValidator("lastName");
        },
        validateEmail() {
            this.errors.email = "";
            this.requiredValidator("email");
        },
        validatePassword() {
            this.errors.password = "";
            this.requiredValidator("password");
        },
        validatePhone() {
            this.errors.phone = "";
            this.requiredValidator("phone") && this.phoneFormatValidator();
        },
        showPasswordStrength() { },
        validateUsername() {
            this.usernameAvailable = false;
            this.errors.username = "";
            return this.requiredValidator("username");
        },
        debounceUsername() {
            const newUsername = this.username;
            clearTimeout(this.usernameDebounceTimeout);
            this.usernameDebounceTimeout = setTimeout(
                () => this.checkUsernameAvailability(newUsername),
                750,
            );
        },
        async checkUsernameAvailability(newUsername) {
            await fetch("api/checkusername.php", {
                method: "POST",
                body: JSON.stringify({ username: newUsername }),
            })
                .then((response) => {
                    if (!response.ok) {
                        console.log("Network response was not ok");
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((available) => {
                    if (this.username !== newUsername) {
                        return;
                    }
                    console.log(available);

                    if (available === false) {
                        this.usernameAvailable = false;
                        this.errors.username = "Username is already taken";
                    } else {
                        this.usernameAvailable = true;
                    }
                })
                .catch((err) => {
                    if (this.username == newUsername) {
                        console.log("error checking username availability");
                        this.errors.username =
                            "error checking username availability";
                    }
                });
        },
    };
}
