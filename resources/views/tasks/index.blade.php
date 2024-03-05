<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Joshua Todo Demo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="d-flex" @submit.prevent="createTask">
                        <input class="form-control me-2" v-model="description" placeholder="New Task" aria-label="Create">
                        <button class="btn btn-outline-success">Create</button>
                    </form>

                    {{--  ERROR MODEL  --}}
                    <div class="modal" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div v-if="errors.length" class="alert alert-danger" role="alert">
                                        <ul>
                                            <li v-for="error in errors">@{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{--  MAIN  --}}
        <div v-for="(item, index) in myData" :key="item.id">
            <div :class="['card', { 'border-success': item.isCompleted }]" style="margin-bottom: 20px;">
                <div class="card-body">
                    <p>
                        <span v-if="item.isCompleted" class="badge bg-success">Completed</span>
                        @{{ item.description }}
                    </p>

                    <form @submit.prevent="openDeleteModal(item.id)" style="margin-bottom: 20px;">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>

                    <button type="button" class="btn btn-primary" @click="openEditModal(item.id)">
                        Edit Task
                    </button>

                    {{--  EDIT MODEL  --}}
                    <div class="modal" :id="'editModal' + item.id" tabindex="-1" :aria-labelledby="'editModalLabel' + item.id" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" :id="'editModalLabel' + item.id">Edit：@{{ item.id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @{{ item.description }}
                                    <input class="form-control me-2" v-model="currentDes" placeholder="" aria-label="Edit">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" @click="editSave(item.id)">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--  DELETE MODEL  --}}
                    <div class="modal" ref="deleteModal" :id="'deleteModal' + item.id" tabindex="-1" :aria-labelledby="'deleteModalLabel' + item.id" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" :id="'deleteModalLabel' + item.id">Delete：@{{ item.id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @{{ item.description }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" @click="deleteTask(item.id)">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data: {
                hello: 'Hello Joshua Vue',
                myData: [],
                description: '',
                currentDes: '',
                errors: []
            },
            mounted() {
                this.fetchTasks();
            },
            methods: {
                fetchTasks: async function() {
                    try {
                        const response = await axios.get('tasks/getList');
                        console.log('Data ---->', response.data)
                        this.myData = response.data;
                    } catch (error) {
                        console.error('Error fetching tasks:', error);
                    }
                },
                openErrorModal: function() {
                    var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    myModal.show();
                },
                openEditModal: function(id) {
                    this.currentDes = this.myData.find(item => item.id === id).description;
                    var myModal = new bootstrap.Modal(document.getElementById('editModal' + id));
                    myModal.show();
                },
                openDeleteModal: function(id) {
                    this.currentDes = this.myData.find(item => item.id === id).description;
                    var myModal = new bootstrap.Modal(document.getElementById('deleteModal' + id));
                    myModal.show();
                },
                createTask: async function() {
                    try {
                        const response = await axios.post('/tasks', {
                            description: this.description
                        });
                        this.description = '';
                        this.fetchTasks();
                    } catch (error) {
                        if (error.response && error.response.data && error.response.data.errors) {
                            this.errors = Object.values(error.response.data.errors).flat();
                            this.openErrorModal();
                            this.description = '';
                        } else {
                            console.error('Error creating task:', error);
                        }
                    }
                },
                editSave: async function(itemId) {
                    try {
                        const response = await axios.post('/tasks/update/' + itemId, {
                            description: this.currentDes
                        });
                        this.currentDes = '';
                        var myModal = new bootstrap.Modal(document.getElementById('editModal' + itemId));
                        myModal.hide();
                        window.location.reload();
                        this.fetchTasks();
                    } catch (error) {
                        console.error('Error updating task:', error);
                    }
                    console.log('EDIT!')
                },
                deleteTask: async function(itemId) {
                    try {
                        const response = await axios.delete('/tasks/' + itemId);
                        var myModal = new bootstrap.Modal(document.getElementById('deleteModal' + itemId));
                        myModal.hide();
                        window.location.reload();
                    } catch (error) {
                        console.error('Error delete task:', error);
                    }

                    console.log('DELETE!')
                }
            }
        })
    </script>
</body>
</html>
