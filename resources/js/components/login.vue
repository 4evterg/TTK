<template>
     <div class="center form">
        <div>
            <div><h1>Войти в аккаунт</h1></div>
            <div  class="form__row">
                <input  placeholder="Ваш E-mail" type="email" v-model="form.email">
            </div>
            <div  class="form__row">
                <input  placeholder="Пароль" type="password" v-model="form.password" name="password">
            </div>
            <div  class="form__row">
                <button @click.prevent="loginUser" type="submit" >Войти</button>
            </div>
        </div> 
    </div>
</template>
<script>
export default {
    data(){
        return{
            form:{
                email: '',
                password: ''
            },
            errors: []
        }
    },
    methods:{
         loginUser(){
             axios.post('/api/login', this.form).then(() =>{
                 this.$router.push({ name: "Dashboard"}); 
             }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
         }
    }
}
</script>
