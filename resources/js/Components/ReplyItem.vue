<script setup>
import {ref} from "vue";
import axios from "axios";
import { router, usePage, useForm } from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const {reply} = defineProps({ reply: Object})

const page = usePage()

const isActive = ref(false)

const children = ref([])
const loaded = ref(false)

async function loadReplies() {
    const {data} = await axios.get(`/replies/${reply.id}/replies`)
    children.value = data
    loaded.value = true
}

function deleteReply() {
    router.delete(route("replies.destroy", { reply: reply.id}), {
        preserveScroll: true,
    })
}

const formReply = useForm({
  content: "",
})

const submit = () => {
  formReply.post(route("replies.storeForReply", {reply: reply.id}), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
        loadReplies()
        formReply.reset()
        isActive.value = false
    }
  })
}
</script>
<template>
    <div>
        <p>{{ reply.content }} <b>{{ reply.user.name }}</b> <button v-if="reply.user.id == page.props.auth?.user.id" @click="deleteReply">Delete</button> <span @click="isActive = !isActive">reply to this</span></p>

        <form @submit.prevent="submit">
            <div :class="isActive ? 'block' : 'hidden'">
                <InputLabel for="content" value="What do you want to add?" />

                <TextInput v-model="formReply.content" type="text" class="mt-1 block w-full" />

                <InputError :message="formReply.errors.content" class="mt-2" />

                <PrimaryButton class="!text-white !bg-green-400 hover:!bg-green-800" :class="{'opacity-25': formReply.processing}" :disabled="formReply.processing">
                    Send
                </PrimaryButton>
            </div>
        </form>

        <button v-if="reply.replies_count > 0 && !loaded" @click="loadReplies"> See more...({{ reply.replies_count }})</button>

        <div v-if="loaded">
            <ReplyItem v-for="child in children" :key="child.id" :reply="child" />
        </div>
    </div>
</template>