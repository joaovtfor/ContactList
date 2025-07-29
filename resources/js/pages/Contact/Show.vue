<script setup lang="ts">

import { Button } from "@/components/ui/button"

import { Link, router } from "@inertiajs/vue3";

import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import Label from "@/components/ui/label/Label.vue";
import Separator from "@/components/ui/separator/Separator.vue";

interface Contact {
    id: number;
    name: string;
    phone: string;
    email: string;
    category: string;
    state: string | { id: number, sigla: string };
    city: string;
    created_at: string;
    updated_at: string;
};

const props = defineProps<{ contact: Contact }>();
const contact = props.contact;

const handleDelete = (id: number) => {
    router.delete(route('contacts.destroy', { id }));
    route('contacts.index');
};

function formatPhone(input: string | number): string {
    const str = String(input).replace(/\D/g, '');

    const regex = /^(\d{2})(\d{1})(\d{4})(\d{4})$/;
    const match = str.match(regex);

    if (!match) {
        throw new Error('Formato de telefone inválido. Deve conter 11 dígitos, ex: 54999303946');
    }

    const [_, ddd, nove, parte1, parte2] = match;
    return `(${ddd}) ${nove} ${parte1}-${parte2}`;
};

function formatDate(isoString: string) {
    const [datePart] = isoString.split('T');
    const [year, month, day] = datePart.split('-');
    return `${day}/${month}/${year}`;
};
</script>

<template>

    <Head title="Adicione um contato" />
    <div class="px-5 py-3 md:px-10 md:py-6 xl:px-40 xl:py-12 flex flex-col gap-y-6">
        <div class="flex items-center justify-between">
            <Link :href="route('contacts.index')" class="text-gray-500 dark:text-gray-300 hover:text-gray-700">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full text-black hover:bg-gray-100 dark:text-white dark:hover:text-gray-100 dark:hover:bg-gray-100/10">
                <font-awesome-icon icon="fa-solid fa-arrow-left" class="fa-xl" />
            </div>
            </Link>
            <div></div>
            <div>
                <Link :href="route('contacts.edit', { id: props.contact.id })">
                <Button variant="ghost" class="p-2">
                    <font-awesome-icon icon="fa-solid fa-pen-to-square" class="fa-lg" />
                </Button>
                </Link>
                <AlertDialog>
                    <AlertDialogTrigger as-child>
                        <Button variant="ghost" class="p-2">
                            <font-awesome-icon icon="fa-solid fa-trash" class="fa-lg text-red-500" />
                        </Button>
                    </AlertDialogTrigger>
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Esta ação irá remover o registro dos nossos bancos de dados
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Cancelar</AlertDialogCancel>
                            <AlertDialogAction @click="handleDelete(contact.id)">Continue
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>
            </div>
        </div>

        <Card :key="contact.id"
            class="px-4 md:px-24 py-12 w-full shadow-[0_0_10px_0_rgba(0,0,0,0.20)] dark:bg-[#1d1d1d] rounded-lg">
            <CardContent>
                <div class="flex flex-col gap-x-4 items-center">
                    <div
                        class="w-24 h-24 md:w-40 md:h-40 text-xs sm:text-md md:text-lg bg-[#1d1d1d] dark:bg-[#0f0f0f] rounded-full flex items-center justify-center text-white font-bold">
                        <font-awesome-icon icon="fa-regular fa-user" class="fa-5x" />
                    </div>
                    <div class="w-full">
                        <div class="w-full flex items-center justify-center flex-wrap">
                            <div class="flex flex-col justify-center items-center gap-y-2 mt-3">
                                <h2 class="text-2xl font-bold">{{ contact.name }}</h2>
                                <p class="text-lg bg-gray-200 py1 px-3 rounded-lg dark:text-black">{{ contact.category
                                }}</p>
                            </div>
                        </div>
                        <div class="w-full flex items-center justify-center flex-wrap mt-6">
                            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="w-full md:w-auto flex items-center md:justify-center gap-x-3 text-black/90 dark:text-white">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon class="dark:text-[#0f0f0f]" icon="fa-solid fa-envelope" />
                                    </div>
                                    <div>
                                        <Label class="text-gray-500 dark:text-gray-300">E-Mail</Label>
                                        <span>{{ contact.email }}</span>
                                    </div>
                                </div>

                                <div
                                    class="w-full md:w-auto flex items-center md:justify-center gap-x-3 text-black/90 dark:text-white">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon class="dark:text-[#0f0f0f]" icon="fa-solid fa-phone" />
                                    </div>
                                    <div>
                                        <Label class="text-gray-500 dark:text-gray-300">Telefone</Label>
                                        <span>{{ formatPhone(contact.phone) }}</span>
                                    </div>
                                </div>

                                <div
                                    class="w-full md:w-auto flex items-center md:justify-center gap-x-3 text-black/90 dark:text-white">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon class="dark:text-[#0f0f0f]"
                                            icon="fa-solid fa-location-dot" />
                                    </div>
                                    <div>
                                        <Label class="text-gray-500 dark:text-gray-300">Localização</Label>
                                        <span>{{ contact.city }} / {{ contact.state }}</span>
                                    </div>
                                </div>
                            </div>

                            <Separator class="w-full grid grid-cols-1 my-6 md:my-10 dark:bg-gray-100/30" />

                            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    class="w-full md:w-auto flex items-center md:justify-center gap-x-3 text-black/90 dark:text-white">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon class="dark:text-[#0f0f0f]" icon="fa-solid fa-calendar" />
                                    </div>
                                    <div>
                                        <Label class="text-gray-500 dark:text-gray-300">Criado em:</Label>
                                        <span>{{ formatDate(contact.created_at) }}</span>
                                    </div>
                                </div>
                                <div
                                    class="w-full md:w-auto flex items-center md:justify-center gap-x-3 text-black/90 dark:text-white">
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <font-awesome-icon class="dark:text-[#0f0f0f]" icon="fa-solid fa-calendar" />
                                    </div>
                                    <div>
                                        <Label class="text-gray-500 dark:text-gray-300">Atualizado em:</Label>
                                        <span>{{ formatDate(contact.updated_at) }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>