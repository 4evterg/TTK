<template>
      <div  class="center">
        <div class="form">
            <div><h1>Регистрация</h1></div>
            <div class="form__row">
                <span class="form__error" v-if="errors.name">{{errors.name[0]}}</span>
                <input  placeholder="Имя" type="text" v-model="form.name" >
            </div>
            <div class="form__row">
                <input  placeholder="E-mail" type="email" v-model="form.email">
            </div>
            <div class="form__row">
                <input  placeholder="Пароль" type="password" v-model="form.password" name="password">
            </div>
            <div class="form__row">
                <input  placeholder="Подтверждение пароля" type="password" v-model="form.password_confirmation" name="password_confirmation">
            </div>
            <div class="form__row">
                <button @click.prevent="saveForm" type="submit" >Зарегистрироваться</button>
            </div>
        </div> 
    </div>
</template>
<script>
export default {
    data(){
        return{
            form:{
                name: '',
                email: '',
                password:'',
                password_confirmation:''
            },
            errors:[]
        }
    },
    methods:{
        saveForm(){
            axios.post('/api/register', this.form).then(() =>{
                console.log('saved');
            }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
        }
    }
}
</script>
