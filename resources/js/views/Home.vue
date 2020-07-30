<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <img class="mx-auto h-12 w-auto" src="../../img/logo.png" alt="Workflow" />
        <h2 class="mt-6n text-center text-3xl leading-9 font-extrabold text-gray-900">
          Breaking Bad Characters
        </h2>
        <p class="mt-2 text-center text-sm leading-5 text-gray-600 max-w">
          <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
              See all your favorite characters
          </a>
        </p>
      </div>
      <form class="mt-8" action="#" method="POST" @submit.prevent="search">
        <div v-if="errorMessage" class="text-red-500 text-sm mb-4">{{ errorMessage }}</div>
        <input type="hidden" name="remember" value="true" />
        <div class="rounded-md shadow-sm">
            <div>
                <input v-model="portrayed" aria-label="Actor" name="portrayed" type="text" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Actor" />
            </div>
             <div>
                <input v-model="name" aria-label="Character" name="character" type="text" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Character" />
            </div>
          <div>
            <input v-model="status" aria-label="Status" name="status" type="text" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Status" />
          </div>
        </div>

        <div class="mt-6">
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
            Search
          </button>
        </div>
      </form>
        <table class="mt-6 shadow-lg bg-white" style="width:100%">
            <tr>
                <th class="bg-blue-100 border text-left px-8 py-4">Actor</th>
                <th class="bg-blue-100 border text-left px-8 py-4">Character</th>
                <th class="bg-blue-100 border text-left px-8 py-4">Status</th>
            </tr>
            <tr v-for="character in characters">
                <td class="border px-8 py-4">{{ character.portrayed }}</td>
                <td class="border px-8 py-4">{{ character.name }}</td>
                <td class="border px-8 py-4">{{ character.status }}</td>
            </tr>
        </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
axios.defaults.withCredentials = false
axios.defaults.baseURL = 'http://api.breakingbad.local'
export default {
  name: 'Home',
  data() {
    return {
      portrayed: '',
      name: '',
      status: '',
      characters: '',
      errorMessage: '',
    }
  },
   mounted() {
       this.search();
    },
  methods: {
    search() {
        axios.get('/characters', {
            params: {
                portrayed: this.portrayed,
                name: this.name,
                status: this.status
            }
        }).then(response2 => {
            this.characters = response2.data.data;
          console.log(this.characters);
        }).catch(error => {
          console.log(error.response.data);
          const key = Object.keys(error.response.data.errors)[0]
          this.errorMessage = error.response.data.errors[key][0]
        })
    }
  }
}
</script>
