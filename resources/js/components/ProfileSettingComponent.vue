<template>
 <div class="row">
   <div class="img-holder">
      <div class="bg"></div>
      <div class="info-holder simple-info">
         <a href="/u/logout" class="btn-logout"> Logout </a>
         <div>
            <div class="image-container">
                <!-- display the current user's image -->
               <img :src="src" alt="Avatar" class="avatar">
               <div class="overlay">
                  <div class="image-upload">
                     <label for="upload">
                     <i class="fa fa-camera" aria-hidden="true"> </i>
                     
                     <!-- on input, we call the Method : uploadImage() to update the user's image -->
                     <input  @change="uploadImage($event)" type="file" id="upload" style="display:none">

                     </label>
                  </div>
               </div>
            </div>
         </div>
         <div>
            <h3> <span>Hi</span> {{ user.fullname }}</h3>
         </div>
         <div>
            <p>You can update your profile and change your password below</p>
         </div>
      </div>
   </div>
   <div class="form-holder">
      <div class="form-content">
         <div class="form-items">
                <!-- 
                Signup From contain 3 inputs
                1. fullname
                2. Email Address 
                3. Date of Birth
                
                Errors are set on the Errors Variable
                
                User details are validated through the backend and errors displayed dynamically on the front
                
                The Captcha code is generated on mount and is validated first on form submit 
                
                -->
            <form>
               <div class="form-group">
                  <input class="form-control" type="text" v-model="user.fullname" placeholder="Enter Fullname" required>
                  <div class="invalid-feedback" v-if="errors.fullname">{{ errors.fullname[0] }}</div>
               </div>
               <div class="form-group">
                  <input class="form-control" type="text" v-model="user.email_address" placeholder="Enter Email address" required>
                  <div class="invalid-feedback" v-if="errors.email_address">{{ errors.email_address[0] }}</div>
               </div>
               <div class="form-group">
                  <input class="form-control" type="text" v-model="user.date_of_birth"  onfocusin="(this.type='date')" onfocusout="(this.type='text')" placeholder="Select date_of_birth" required>
                  <div class="invalid-feedback" v-if="errors.date_of_birth">{{ errors.date_of_birth[0] }}</div>
               </div>

               <!--  Display successful login message if the success variable is true  -->
               <div class="alert alert-success show with-icon" v-if="success">
                  Profile updated.
               </div>

               <!--  Display error if the errors.error object is not null  -->
               <div class="alert alert-success show with-icon" v-if="errors.error">
                  Oops look like something went wrong, please try again later or contact admin.
               </div>
               <div class="form-group">
                  
                     <div class="form-button">
                        <button id="submit" type="button" @click="submit()" class="ibtn ">Update Profile</button>
                        <a href="JavaScript:void(0)" data-toggle="modal" data-target="#changePasswordModal">Change Password</a>
                     </div>
              
               </div>
            </form>
         </div>
      </div>
   </div>
 </div>   
</template>


<script>


export default ({

//Prop for authorised User to bring in user data


     props: [
        'user',
        ],

//Define our variables
    data(){
       return {
            src: '../images/'+this.user.avatar, 
           image_file: "",
           errors : [],
           success: false,
           isLoading: false,
                fullPage: true
       }
    },

    methods: {


   //On form submit we call the method below 
   //It firsts clears errors being displayed
   //Turns Loading and Success variable's status 
   //When loading is set to true, a spinner should show on the form instead of the submit button
   //Create a self variable to use under the axios function
   //sends the data to the /u/profile route
        submit: function(){

              
 this.errors = [];
                this.success = false, this.loading = true;
                 let self = this;
                 
                 $(".form-control").attr("disabled", "true");
         
                axios({
                    method: 'post',
                    url: '/u/profile/update',
                    data: {
                    fullname: this.user.fullname,
                    email_address: this.user.email_address,
                    date_of_birth: this.user.date_of_birth,

                    }
                })
                .then(function (response) {                                          
                    if(response.data.status == "success")
                        self.success = true;

                    if(response.data.status == "errors")
                    self.errors = true;
                }).catch(function (errors){
                    self.errors = errors.response.data.errors;
                    console.log(self.errors);
                });
            
                //Undisable inputs
            //Set Loading status to false;
            $(".form-control").attr("disabled",false);
            this.loading = false;
 
               
               
            },

            //The function is to set a new profile avatar for the user
            //we get the image being uploaded and send it to the /u/profile/update to save the new image
            //we use FormData to send the request over
        uploadImage: function(event){
    
      
         this.src = URL.createObjectURL(event.target.files[0]);
       this.image_file =  event.target.files[0];
  let data = new FormData();
         data.append('file', this.image_file); 
      
       
       //Axios function to post the data
        axios({
                    method: 'post',
                    url: '/u/profile/update',
                    data: data,
                    header : {
                        'Content-Type' : 'image/png'
                    }
                })
                .then(function (response) {                                          
                    if(response.data.status == "success")
                        self.success = true;

                    if(response.data.status == "errors")
                    self.errors = true;
                }).catch(function (errors){
                    self.errors = errors.response.data.errors;
                    console.log(self.errors);
                });

            
        
      },

    },
    //We watch if the success variable changes
    //We then set a 3 sec delay to change the variable status so we stop the alert box from displaying
    watch: {
        success: function(val){
            let self = this;
           setTimeout(function(){ self.success= false; }, 3000);
        }
    }
})
</script>

<style>

.fa-camera{
    position: relative;
top: 70px;
cursor: pointer;
font-size: 25px;
}
.avatar {
    width: 150px;
    height: 150px;
    margin-bottom: 20px;
    border-radius: 50%;
    border: 2px solid rgba(0,0,0,.05);
}

.image-container {
  position: relative;
width: 152px;
height: 173px;
margin: 0px auto;
}

.image {
  display: block;

}

.overlay {
  position: absolute !important;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
width: 152px;
height: 152px;
  opacity: 0;
  transition: .3s ease;
  background-color: rgba(255, 255, 255, 0.5);
    border-radius: 50%;

}

.image-container:hover .overlay {
  opacity: 0.5;
}


.fa-camera:hover {
  color: grey;
}

.image-upload>input {
  display: none;
}

.g-core-image-upload-form input{
cursor: pointer;
}

.btn-logout{
    float: right;
font-size: 20px;
color: white;
}
</style>