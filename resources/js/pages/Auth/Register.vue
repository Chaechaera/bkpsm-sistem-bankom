<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    subunitkerjas: Array,
});

const form = useForm({
    nip: '',
    nama: '',
    email: '',
    subunitkerja_id: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="nip" value="Nip" />

                <TextInput id="nip" type="text" class="mt-1 block w-full" v-model="form.nip" required autofocus
                    autocomplete="nip" />

                <InputError class="mt-2" :message="form.errors.nip" />
            </div>

            <div class="mt-4">
                <InputLabel for="nama" value="Nama" />

                <TextInput id="nama" type="text" class="mt-1 block w-full" v-model="form.nama" required autofocus
                    autocomplete="nama" />

                <InputError class="mt-2" :message="form.errors.nama" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="subunitkerja_id" value="Sub Unit Kerja" />

                <select id="subunitkerja_id" name="subunitkerja_id" v-model="form.subunitkerja_id"
                    class="mt-1 block w-full" required>
                    <option disabled value="">-- Pilih Sub Unit Kerja --</option>
                    <option v-for="s in subunitkerjas" :key="s.id" :value="s.id">
                        {{ s.sub_unitkerja }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.subunitkerja_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
