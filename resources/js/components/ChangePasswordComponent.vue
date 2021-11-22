<template>
    <!-- Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

         <!-- 
                Signup From contain 3 inputs
                1. Password
                2. Password Confirmation
        
                
                Errors are set on the Errors Variable
                
                User details are validated through the backend and errors displayed dynamically on the front
                
                
                -->


           <form>

                              

     <div class="form-group">              		
                         <div class="input-group ">
  
  <!-- if ShowPassword variable is set to true, we change input type to text and vice versa if it's set to false -->
                    <input  :type="showPassword ? 'text' : 'password'"  class="form-control" placeholder="New Password"  @input="password_check()" v-model="password" />
     
        
        
       
        

           <div class="eyeButton">
          <span @mousedown="toggleShow" @mouseup="toggleShow" @touchstart="toggleShow" @touchend="toggleShow" style="cursor: pointer;"><i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i></span>
        </div>
          
  
</div>
<div class="invalid-feedback" v-if="errors.password">{{ errors.password[0] }}</div>      
           <ul v-if="!has_special || !password.length > 7 || !has_uppercase || !has_lowercase || !has_number">
       <li> <p class="frmValidation" :class="{'frmValidation--passed' : password.length > 7}"><i class="frmIcon fas" :class="password.length > 7 ? 'fa-check' : 'fa-times'"></i> Longer than 7 characters</p></li>
       <li> <p class="frmValidation" :class="{'frmValidation--passed' :has_uppercase }"><i class="frmIcon fas" :class="has_uppercase ? 'fa-check' : 'fa-times'"></i> Has a capital letter</p></li>
       <li> <p class="frmValidation" :class="{'frmValidation--passed' :has_lowercase }"><i class="frmIcon fas" :class="has_lowercase ? 'fa-check' : 'fa-times'"></i> Has a lowercase letter</p></li>
       <li> <p class="frmValidation" :class="{'frmValidation--passed' : has_number }"><i class="frmIcon fas" :class="has_number ? 'fa-check' : 'fa-times'"></i> Has a number</p> </li>
       <li> <p class="frmValidation" :class="{'frmValidation--passed' : has_special }"><i class="frmIcon fas" :class="has_special ? 'fa-check' : 'fa-times'"></i> Has a special character</p></li>
     </ul>
            


 
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
       <li> <p class="frmValidation" :class="{'frmValidation--passed' : !password_match}"><i class="frmIcon fas" :class="password_match ? 'fa-times' : 'fa-check' "></i> Password Match</p></li>

     </ul>
          
        
           <div class="invalid-feedback" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</div>  
        </div>


<!--  Display successful login message if the success variable is true  -->
            <div class="alert alert-success show with-icon" v-if="success">
        Password changed successfully
    </div>

       <!--  Display error if the errors.error object is not null  -->
     <div class="alert alert-success show with-icon" v-if="errors.error">
        Oops look like something went wrong, please try again later or contact admin.
    </div>
  
        </form>
      </div>
      <div class="modal-footer">
      
          <vue-simple-spinner size="medium" v-if="loading" />
        <button type="button" v-if="!loading" @click="submit()" class="btn btn-primary">Save changes</button>
      </div>
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
    data(){
        return {
            showPassword: false,  //to show the user, their inserted password. if set to true
        showConfirmPassword: false, //to show the user, their inserted confirmation password. if set to true
            password: '',
            password_confirmation: '',
            errors: [],
            success: false,
            loading: false,

              //Password strength validation variables
                has_number:    false,
                has_lowercase: false,
                has_uppercase: false,
                has_special:   false,
                password_match: false,
                typing: false,
        }
    },
    methods:{

       //When the user submits the form, this method Submit() is called
        //On the method, 
           //   errors is set to empty
           //   success is set to false to disable any messages being shown to the user
           //   loading is set to true to show the spinner and disable button
           //   We disable all input boxes on the form while the data is being processed.
           
      submit: function(){
      this.loading = true;
      let self = this;
      this.errors = [];
        $(".form-control").attr("disabled", true);
        axios({
                    method: 'post',
                    url: '/u/password/update',
                    data: {
    
                    password: this.password,
                    password_confirmation: this.password_confirmation,

                    }
                })
                .then(function (response) {                                          
                      
                       //set all input fields to null
                         self.success = true;
                         self.password = "";
                         self.password_confirmation = "";
                  
                     
                 
                    
                }).catch(function (errors){
                    self.errors = errors.response.data.errors;
                    console.log(self.errors);
                });

              //Undisable inputs
            //Set Loading status to false;
            $(".form-control").disabled=false;
            this.loading = false;
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

//check password match
                confirmpassword_check: function(){
                     this.password_match    =  this.password != this.password_confirmation ? true : false;
                }
    },
     watch: {
       //We watch the success value, if it changes, we display the message for about 3 seconds then set it's value to false
        success: function(val){
            let self = this;
           setTimeout(function(){ self.success= false; }, 3000);
        },
        //We watch the password_confirmation value, if it changes, we turn is typing to false unless the value is null
        password_confirmation: function(val){
              if(val.length > 0)
                this.typing = true;
                else 
                this.typing = false;
          }
    }
  }
</script>
<style >
.invalid-feedback{
    color: red;
    display: block;
}
.eyeButton {
  position: absolute;
  right: 10px;
  bottom: 4px;
  top: 6px;
  z-index: 999;
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