
<template>
  <div class="form-holder"> 
   <div class="form-content"> 
      <div class="form-items">
         <h3>Welcome back to SA Lantern</h3>
         <p>To access your tools, please login using your credentials below</p>
         <div class="page-links">
            <a href="/" class="active">Login</a><a href="/account/signup">Signup</a>
         </div>

          <!-- 
          Login From contain 3 inputs
          1. Email Address 
          2. Address 
          3. Captcha input
          
          Errors are set on the Errors Variable
          
          User details are validated through the backend and errors displayed dynamically on the front

          The Captcha code is generated on mount and is validated first on form submit 

          -->
         <form>
            <div class="form-group">
               <input class="form-control" type="text" v-model="email_address" placeholder="E-mail Address" required>
               <div class="invalid-feedback" v-if="errors.email_address">{{ errors.email_address[0] }}</div>
            </div>
            <div class="form-group">
               <input class="form-control" v-model="password"  type="password" name="password" placeholder="Password" required>
               <div class="invalid-feedback" v-if="errors.password">{{ errors.password[0] }}</div>
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
               Login was successful, redirecting to profile page..
            </div>

            <!--  Display error if the errors.error object is not null  -->
            <div class="alert alert-danger show with-icon" v-if="errors.error">
               {{ errors.error }}
            </div>
            <div class="form-button" v-if="!success">

                <!-- display spinner if loading is set to true -->
               <vue-simple-spinner size="medium" v-if="loading" />

                <!-- display button if loading is set to false -->
               <button v-if="!loading" id="submit" type="button" @click="Submit" class="ibtn">Login</button> 
            </div>
         </form>
      </div>
   </div>
</div>   <!-- end form holder -->
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
            captcha : '', 
            email_address: '',
            password: '',
            errors: [],
            success: false,
            loading: false,
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
           this.success = false, this.loading = true;
           let self = this;
                 
           $(".form-control").attr("disabled", true);

           //Call method,  ValidateRecaptcha() : validates the captcha and if it passes, continue with the post request
            if(this.ValidateRecaptcha()){

                //Axios Post call to route /account/login
                //Values Passed: Email Address and password
                
            axios({
              method: 'post',
              url: '/account/login',
              data: {
              email_address: this.email_address,
              password: this.password,
              }
            })
            .then(function (response) {                   
            //A Successful response of type Array is expected
            //Array status should include the values : Success and Invalid

                if(response.data.status == "success")          
                    setTimeout(function(){ window.location.href = "/home"; }, 1500); //Redirect the user after a successful login
                if(response.data.status == "invalid"){
                    self.errors = {error : "incorrect Email Address / Password"};  //Disaply errors if login credentials are incorrect
                    self.GenerateCaptcha();
                }
                
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

</style>
