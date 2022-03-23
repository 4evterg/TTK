<template>
    <div v-if="isAdmin">
      <b>{{msg}}</b>
      <form @submit.prevent="saveData">
        <input v-model="form.title" class="test" autocomplete="off" type="text" aria-label="WRITE SHIT" @keydown="form.errors.clear('name')">
        <button type="submit">DO SHIT!</button>        
       <span v-if="form.errors.has('name')" v-text="form.errors"></span>  
      </form>
      <div>
          <div v-for="author in authors" :key="author.id">
              {{author.name}}<br>
          </div>
          <form @submit.prevent="getData">
            <button type="submit">GET SHIT!</button>
          </form>
      </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                isAdmin: false,
                user: null,
                authors:'',
                form: new Form({
                    title: '',
                })
            }
        },
        methods:{
            getData(){
                axios.get('/api/author').then((res)=>{
                    this.authors = res.data
                }).catch((error) => {
                    console.log(error)
                })
            },
            saveData(){
                let data = new FormData();
                data.append("name", this.form.title)
                axios.post('/api/author', data).then((res) => {
                 this.form.reset()
                 this.getData()
                }).catch((error) => {
                    console.log(error)
                    this.form.errors.record(error.response.data.errors)
                })
            }
        },
        props: {
            msg: String
        },
        mounted() {
            console.log('Component mounted.')
            axios.get('/api/user').then((res)=>{
                this.user = res.data
                this.isAdmin = res.data['is_admin']
            }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
        }
    }
</script>

<style>
    .test{
        border: 1px solid #000;
    }
</style>
