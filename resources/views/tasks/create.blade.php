@extends('layouts.app')

@section('content')
    <div id="app">
        <h1>New Task</h1>
        <div v-if="errors.length" class="alert alert-danger" role="alert">
            <ul>
                <li v-for="error in errors">@{{ error }}</li>
            </ul>
        </div>
        <form @submit.prevent="createTask" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="description">Task Description</label>
                <input class="form-control" v-model="description" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </form>
    </div>

    <script>
        const app = {
            data() {
                return {
                    description: '',
                    errors: []
                }
            },
            methods: {
                async createTask() {
                    try {
                        const response = await axios.post('/tasks', {
                            description: this.description
                        });
                        this.description = '';

                        window.location.href = '/';
                    } catch (error) {
                        if (error.response && error.response.data && error.response.data.errors) {
                            this.errors = Object.values(error.response.data.errors).flat();
                        } else {
                            console.error('Error creating task:', error);
                        }
                    }
                }
            }
        }

        Vue.createApp(app).mount('#app')
    </script>
@endsection
