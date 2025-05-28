<template>
  <v-container class="fill-height d-flex flex-column align-center justify-center text-center">
    <h1 class="text-h3 font-weight-bold mb-2">403 - Not Authorized</h1>
    <p class="text-subtitle-1 mb-6">You do not have permission to access this page.</p>
    <v-btn @click="$router.push('/')"> Back to Home </v-btn>
  </v-container>
</template>
