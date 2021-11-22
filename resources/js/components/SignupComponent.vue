<template>
 <div class="form-holder">
    <div class="form-content">
        <div class="form-items">
            <h3>Welcome to SA Lantern.</h3>
            <p>To gain access to your tools, please signup below</p>
            <div class="page-links">
                <a href="/" >Login</a><a href="#" class="active">Signup</a>
            </div>
            <!-- 
                Signup From contain 3 inputs
                1. fullname
                2. Email Address 
                3. Date of Birth
                4. Password
                5. Password Confirmation
                4. Captcha input
                
                Errors are set on the Errors Variable
                
                User details are validated through the backend and errors displayed dynamically on the front
                
                The Captcha code is generated on mount and is validated first on form submit 
                
                -->
            <form>
                <div class="form-group">
                <input class="form-control" type="text" v-model="fullname" placeholder="Enter Fullname" required>
                <div class="invalid-feedback" v-if="errors.fullname">{{ errors.fullname[0] }}</div>
                </div>
                <div class="form-group">
                <input class="form-control" type="text" v-model="email_address" placeholder="Enter Email address" required>
                <div class="invalid-feedback" v-if="errors.email_address">{{ errors.email_address[0] }}</div>
                </div>
                <div class="form-group">
                <input class="form-control" type="text" v-model="date_of_birth"  onfocusin="(this.type='date')" onfocusout="(this.type='text')" placeholder="Select date_of_birth" required>
                <div class="invalid-feedback" v-if="errors.date_of_birth">{{ errors.date_of_birth[0] }}</div>
                </div>
                <div class="form-group">
                <div class="input-group ">
                    <!-- if ShowPassword variable is set to true, we change input type to text and vice versa if it's set to false -->
                    <input  :type="showPassword ? 'text' : 'password'"  class="form-control" placeholder="New Password"  @input="password_check()" v-model="password" />
                    <div class="eyeButton">
                         <!-- on toggle, we call the method toggleShow() to change input types -->
                        <span @mousedown="toggleShow" @mouseup="toggleShow" @touchstart="toggleShow" @touchend="toggleShow" style="cursor: pointer;"><i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i></span>
                    </div>
                </div>

                <!-- Password Strength guider -->
                <ul v-if="!has_special || !password.length > 7 || !has_uppercase || !has_lowercase || !has_number">
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' : password.length > 7}"><i class="frmIcon fas" :class="password.length > 7 ? 'fa-check' : 'fa-times'"></i> Longer than 7 characters</p>
                    </li>
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' :has_uppercase }"><i class="frmIcon fas" :class="has_uppercase ? 'fa-check' : 'fa-times'"></i> Has a capital letter</p>
                    </li>
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' :has_lowercase }"><i class="frmIcon fas" :class="has_lowercase ? 'fa-check' : 'fa-times'"></i> Has a lowercase letter</p>
                    </li>
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' : has_number }"><i class="frmIcon fas" :class="has_number ? 'fa-check' : 'fa-times'"></i> Has a number</p>
                    </li>
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' : has_special }"><i class="frmIcon fas" :class="has_special ? 'fa-check' : 'fa-times'"></i> Has a special character</p>
                    </li>
                </ul>
                <div class="invalid-feedback" v-if="errors.password">{{ errors.password[0] }}</div>
                </div>
                <div class="form-group">
                <div class="input-group ">
                     <!-- if ShowPassword variable is set to true, we change input type to text and vice versa if it's set to false -->
                    <input :type="showConfirmPassword ? 'text' : 'password'" class="form-control" placeholder="Confirm Password" @input="confirmpassword_check()" v-model="password_confirmation" />
                    <div class="eyeButton">
                        <span @mousedown="toggleConfirmPassword" @mouseup="toggleConfirmPassword" @touchstart="toggleConfirmPassword" @touchend="toggleConfirmPassword" style="cursor: pointer;"><i class="fas" :class="{ 'fa-eye-slash': showConfirmPassword, 'fa-eye': !showConfirmPassword }"></i></span>
                    </div>
                </div>

                <!-- if the user is on the password_confirmation input and is typing, we validate the both passwords and display any errors -->
                <ul v-if="typing">
                    <li>
                        <p class="frmValidation" :class="{'frmValidation--passed' : !password_match}"><i class="frmIcon fas" :class="password_match ? 'fa-times' : 'fa-check' "></i> Password Match</p>
                    </li>
                </ul>
                <div class="invalid-feedback" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</div>
                </div>
                <div class="form-group">
                <div class="input-group ">
                    <div class="input-group-prepend">

                         <!-- div to display generated captcha -->
                        <div id="captcha">
                        </div>
                    </div>
                    <input type="text" class="form-control" v-model="captcha" placeholder="Re-Captcha"  >
                </div>
                <div class="invalid-feedback" v-if="errors.captcha">{{ errors.captcha }}</div>
                </div>

                <!--  Display successful login message if the success variable is true  -->
                <div class="alert alert-success show with-icon" v-if="success">
                Signup was successful, redirecting to profile page..
                </div>

                <!--  Display error if the errors.error object is not null  -->
                <div class="alert alert-success show with-icon" v-if="errors.error">
                Oops look like something went wrong, please try again later or contact admin.
                </div>


                <div class="form-button" v-if="!success">

                <!-- display spinner if loading is set to true -->
                <vue-simple-spinner size="medium" v-if="loading" />

                <!-- display button if loading is set to false -->
                <button v-if="!loading" id="submit" type="button" @click="Submit" class="ibtn">Signup</button> <a href="/">Have an account, Login?</a>
                </div>
            </form>
        </div>
    </div>
 </div>
</template>

<script>
//Import spinner to use when user submits page and is waiting for a response back
//Package included on ReadMe file
import VueSimpleSpinner from 'vue-simple-spinner'

    export default {
        //Call spinner component to reference on the form
        components: {
        VueSimpleSpinner
     },
      mounted() {
        //Method GenerateCaptcha() : generates a new captcha code and set's the user code input to null
        this.GenerateCaptcha();
     },
      data(){
        //Our Variables
        //the Code variable is used to match with the generated captcha code when the user submits the page
        return {
            code: '',
            fullname: '',
            email_address: '',
            date_of_birth: '',
            password: '',
            password_confirmation: '',
            captcha : '',
            errors: [],
            success: false,
            loading: false,
            showPassword: false,  //to show the user, their inserted password. if set to true
        showConfirmPassword: false, //to show the user, their inserted confirmation password. if set to true

        //Password strength validation variables
            has_number:    false,
            has_lowercase: false,
            has_uppercase: false,
            has_special:   false,
            password_match: false,
            typing: false,
        }
        },

     methods: {

       //When the user submits the form, this method Submit() is called
        //On the method, 
           //   errors is set to empty
           //   success is set to false to disable any messages being shown to the user
           //   loading is set to true to show the spinner and disable button
           //   We disable all input boxes on the form while the data is being processed.

        Submit: function(){
           
           
            this.errors = [];
            this.success = false; this.loading = true;
            let self = this;
                 
            $(".form-control").attr("disabled", "true");
            //Call method,  ValidateRecaptcha() : validates the captcha and if it passes, continue with the post request
            if(this.ValidateRecaptcha()){

                //Axios Post call to route /account/login
                //Values Passed: Fullname, Email Address, Date of Birth, Password
                axios({
                    method: 'post',
                    url: '/account/signup',
                    data: {
                    fullname: this.fullname,
                    email_address: this.email_address,
                    date_of_birth: this.date_of_birth,
                    password: this.password,
                    password_confirmation: this.password_confirmation,

                    }
                })
                .then(function (response) {  
                  //A Successful response of type Array is expected
                  //Array status should include the values : Success and errors                                        
                    if(response.data.status == "success")          
                        setTimeout(function(){ window.location.href = "/u/home"; }, 1500); //Redirect the user after a successful signup
                }).catch(function (errors){
                    //set the errors variable to response errors so that they can be displayed dynamically
                    self.errors = errors.response.data.errors;
                    self.GenerateCaptcha();   //generate new code
                });
            }


            //Undisable inputs
            //Set Loading status to false;
            $(".form-control").attr("disabled", false);
            this.loading = false;
 
               
               
            },
            //Method to validate Captcha code against the user's submitted vode
            ValidateRecaptcha: function(){
               if(this.code == this.captcha){
                   return true;
               }else{
                //If the codes don't match, we generate a new one
                //Push Errors to the errors variable
                //Return false statement
                this.GenerateCaptcha();               
                this.errors = {captcha : "Please insert correct captcha code"};
                return false;
               }
            },
           //Method to generate a new captcha code
        //generate random code
        //Create a Canvas with the generated code as text
       GenerateCaptcha: function(){
        //clear current canvas being shown to user
            $("#captcha").html("");

            //Set variables to use
            var charsArray =
            "0123456789ABCDEFGHIJKLMNOPRSTUVWXYZ";
            var lengthOtp = 5;
            var captcha = [];
            var canv = document.createElement("canvas");

           //The string maximum length will be 5
           //below code will not allow Repetition of Characters
            for (var i = 0; i < lengthOtp; i++) {               
                var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
                if (captcha.indexOf(charsArray[index]) == -1)
                captcha.push(charsArray[index]);
                else i--;
            }
           
            canv.id = "captcha";
            canv.width = 100;
            canv.height = 30;

            var ctx = canv.getContext("2d");
            ctx.font = "25px 'Lato', sans-serif";
            ctx.fillStyle  = "white";
            ctx.strokeText(captcha.join(""), 0, 30);
            this.code = captcha.join("");
            this.recaptcha = "";
             document.getElementById("captcha").appendChild(canv); 
           },

           toggleShow: function() {
            this.showPassword = !this.showPassword;
          },

          toggleConfirmPassword: function(){ 
            this.showConfirmPassword = !this.showConfirmPassword;
          },

          password_check: function () {
            this.has_number    = /\d/.test(this.password);
            this.has_lowercase = /[a-z]/.test(this.password);
            this.has_uppercase = /[A-Z]/.test(this.password);
            this.has_special   = /[!@#\$%\^\&*\)\(+=._-]/.test(this.password);
          },

          confirmpassword_check: function(){
            this.password_match = this.password != this.password_confirmation ? true : false;
          }
          
      },

      //We watch the password_confirmation value, if it changes, we turn is typing to false unless the value is null
        watch: {
            password_confirmation: function(val){
                if(val.length > 0)
                 this.typing = true;
                 else 
                 this.typing = false;
            }
        }
    }
</script>

<style scoped>
#captcha{
   background-color: #e7f4f2;
padding: 3px;
    padding-left: 3px;
width: 99px;
border-bottom-left-radius: 10px;
border-top-left-radius: 10px;
padding-left: 5px;
}

.invalid-feedback{
    color: red;
    display: block;
}
.eyeButton {
  position: absolute;
right: 10px;
bottom: 4px;
top: 9px;
z-index: 999;
color: black;
}

 .frmField{background-color:white; color:#495057; line-height: 1.25; font-size: 16px; font-family: 'Roboto', sans-serif; border:0; padding: 10px; border:1px solid #dbdbdb;  border-radius: 3px; width:90%;}
        .frmLabel{display: block; margin-bottom: 10px; font-weight: bold;}
        .frmValidation{font-size: 13px; }
        .frmValidation--passed{color:#717b85;}
        .frmIcon{color:#EB0029;}
            .frmValidation--passed .frmIcon{color:#0fa140;}   

.howToBuild{ text-align:center; color:purple;}
.howToBuild a{ color:grey; font-weight:bold; text-decoration:none; text-transform:uppercase; }

.frmValidation{
    margin-bottom: 0px;
}

ul{
    list-style-type: none;
padding: 0px 3px 3px 3px;
    padding-top: 0px;
padding-top: 10px;
}

li {
    padding-bottom: 2px;
    padding-bottom: 0px;
}
</style>
