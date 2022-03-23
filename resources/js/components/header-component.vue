<template>
    <div class="header">
        <div class="header__container _container">
            <div class="header__logo logo">
                <img src="images/logo.png" alt="">
            </div>
            <div class="header__menu">
                <router-link to='/' exact>Главная</router-link>
                <router-link to='/dashboard'>Панель управления</router-link>
                <router-link to='/login' exact>Логин</router-link>
                <router-link to='/register'>Регистрация</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      
    }
  },
}
</script>
