<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import { Button } from "@/components/ui/button"
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
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

import Input from '@/components/ui/input/Input.vue';

import CardContent from '@/components/ui/card/CardContent.vue';

import { useColorMode } from '@vueuse/core'

import { Link, router, usePage } from '@inertiajs/vue3';

import { Toaster, toast } from 'vue-sonner';

import { ref, reactive, computed } from 'vue';

const mode = useColorMode({
    disableTransition: false,
});

const page = usePage();

const handleDelete = (id: number) => {
    router.delete(route('contacts.destroy', { id }));
    toast.success((page.props.flash as { message?: string }).message ?? '', {
        description: "Contato excluído com sucesso!",
        duration: 2000,
    });
};

const handleCloseToast = () => {
    (page.props.flash as any).message = null;
};

interface Contact {
    id: number;
    name: string;
    phone: string;
    email: string;
    category: string;
    state: { id: number, name: string };
    city: string;
};

interface Props {
    contacts: Contact[];
};

const props = defineProps<{ contacts: Contact[] }>();

const searchQuery = ref("");

const filteredContacts = computed(() => {
    const search = searchQuery.value.trim().toLowerCase();
    if (!search) return props.contacts;

    return props.contacts.filter(contact => {
        const name = (contact.name ?? '').toLowerCase();
        const phone = String(contact.phone ?? '').toLowerCase();
        const email = (contact.email ?? '').toLowerCase();
        const category = (contact.category ?? '').toLowerCase();
        const stateName = (contact.state?.name ?? '').toLowerCase();
        const city = (contact.city ?? '').toLowerCase();

        return (
            name.includes(search) ||
            phone.includes(search) ||
            email.includes(search) ||
            category.includes(search) ||
            stateName.includes(search) ||
            city.includes(search)
        );
    });
});

function formatPhone(input: string | number): string {
    const str = String(input).replace(/\D/g, '');

    const regex = /^(\d{2})(\d{1})(\d{4})(\d{4})$/;
    const match = str.match(regex);

    if (!match) {
        throw new Error('Formato de telefone inválido. Deve conter 11 dígitos.');
    }

    const [_, ddd, nove, parte1, parte2] = match;
    return `(${ddd}) ${nove} ${parte1}-${parte2}`;
};
</script>

<template>
    <div id="main" class="px-5 py-3 md:px-10 md:py-6 xl:px-40 xl:py-12 flex flex-col gap-y-6">

        <div class="grid gap-1">
            <div class="flex items-center justify-between flex-wrap gap-y-3">
                <div class="flex items-center gap-x-2">
                    <font-awesome-icon icon="fa-solid fa-users" class="fa-xl" />
                    <h1 class="text-xl md:text-2xl font-bold">Agenda de contatos</h1>
                    <p class="text-gray-500">({{ filteredContacts.length }})</p>
                </div>
                <div class="flex items-center gap-x-10">
                    <div class="flex items-center gap-x-2">
                        <font-awesome-icon :icon="mode === 'dark' ? 'moon' : 'sun'" class="h-5 w-5"
                            :class="mode === 'dark' ? 'text-gray-200' : 'text-yellow-500'" />
                        <Switch @click="mode === 'dark' ? mode = 'light' : mode = 'dark'" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-gray-700
                            transition-colors">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                :class="mode === 'dark' ? 'translate-x-6' : 'translate-x-1'" />
                        </Switch>
                    </div>
                    <div>
                        <Link :href="route('contacts.create')">
                        <Button variant="outline" size="lg" class="flex items-center gap-x-3 text-md dark:bg-[#1d1d1d]">
                            <font-awesome-icon icon="fa-solid fa-plus" class="fa-xl" />
                            Adicionar contato
                        </Button>
                        </Link>
                    </div>
                </div>
            </div>
            <font-awesome-icon icon="fa-solid fa-magnifying-glass" class="z-10 relative left-3 top-11" />
            <div class="w-full flex items-center justify-between my-3">
                <div class="w-full pr-3 flex items-center relative">
                    <Input class="w-full bg-gray-100 dark:text-white dark:bg-[#1d1d1d] p-2 pl-12 rounded-xl" id="search"
                        type="text" placeholder="Procurar contatos..." v-model="searchQuery" />
                </div>
            </div>
        </div>

        <div id="cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Card v-for="contact in filteredContacts" :key="contact.id" class="p-4 w-full">
                <CardContent>
                    <div class="flex gap-x-4">
                        <div
                            class="w-7 h-6 md:w-11 md:h-10 text-xs sm:text-md md:text-lg bg-[#1d1d1d] dark:bg-[#0f0f0f] rounded-full flex items-center justify-center text-white font-bold">
                            <Link :href="route('contacts.show', { id: contact.id })">
                            <font-awesome-icon icon="fa-regular fa-user" />
                            </Link>
                        </div>
                        <div class="w-full">
                            <div class="w-full flex items-start justify-between flex-wrap">
                                <div class="w-3/4 flex flex-col gap-y-1">
                                    <Link :href="route('contacts.show', { id: contact.id })">
                                    <h2 class="text-xl font-bold">{{ contact.name }} <span><font-awesome-icon
                                                icon="fa-solid fa-arrow-up-right-from-square"
                                                class="fa-xs pl-2" /></span>
                                    </h2>
                                    <p class="text-lg">{{ contact.category }}</p>
                                    </Link>
                                </div>
                                <div class="w-1/4">

                                    <Link :href="route('contacts.edit', { id: contact.id })">
                                    <Button variant="ghost" class="p-2">
                                        <font-awesome-icon icon="fa-solid fa-pen-to-square" class="fa-lg" />
                                    </Button>
                                    </Link>
                                    <AlertDialog class="z-50">
                                        <AlertDialogTrigger as-child>
                                            <Button variant="ghost" class="p-2 z-10">
                                                <font-awesome-icon icon="fa-solid fa-trash"
                                                    class="fa-lg text-red-500" />
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
                                                <AlertDialogAction @click="handleDelete(contact.id)">Continuar
                                                </AlertDialogAction>
                                            </AlertDialogFooter>
                                        </AlertDialogContent>
                                    </AlertDialog>
                                </div>
                            </div>
                            <div class="mt-1">
                                <div class="flex items-center gap-x-3 text-gray-500">
                                    <font-awesome-icon icon="fa-solid fa-envelope" />
                                    <span>{{ contact.email }}</span>
                                </div>
                                <div class="flex items-center gap-x-3 text-gray-500">
                                    <font-awesome-icon icon="fa-solid fa-phone" />
                                    <span>{{ formatPhone(contact.phone) }}</span>
                                </div>
                                <div class="flex items-center gap-x-3 text-gray-500">
                                    <font-awesome-icon icon="fa-solid fa-location-dot" />
                                    <span>{{ contact.city }} / {{ contact.state }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
        <Table id="table">
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]">
                        Nome
                    </TableHead>
                    <TableHead>Telefone</TableHead>
                    <TableHead>E-Mail</TableHead>
                    <TableHead>Estado (UF)</TableHead>
                    <TableHead>Cidade</TableHead>
                    <TableHead>Categoria</TableHead>
                    <TableHead>Ação</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="contact in filteredContacts" :key="contact.id">

                    <TableCell>{{ contact.name }}</TableCell>
                    <TableCell>{{ formatPhone(contact.phone) }}</TableCell>
                    <TableCell>{{ contact.email }}</TableCell>
                    <TableCell>{{ contact.state }}</TableCell>
                    <TableCell>{{ contact.city }}</TableCell>
                    <TableCell>{{ contact.category }}</TableCell>
                    <TableCell>
                        <Link :href="route('contacts.edit', { id: contact.id })">
                        <Button variant="ghost" class="p-2 cursor-pointer">
                            <font-awesome-icon icon="fa-solid fa-pen-to-square" class="fa-lg" />
                        </Button>
                        </Link>
                        <AlertDialog>
                            <AlertDialogTrigger as-child>
                                <Button variant="ghost" class="p-2 cursor-pointer">
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
                                    <AlertDialogAction @click="handleDelete(contact.id)">Continuar</AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                        <Link :href="route('contacts.show', { id: contact.id })">
                        <Button variant="ghost" class="p-2 cursor-pointer">
                            <font-awesome-icon icon="fa-solid fa-user" class="fa-lg" />
                        </Button>
                        </Link>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <div class="flex items-center justify-end">
            <div v-if="(page.props.flash as any).message"
                class="relative right-0 p-4 rounded-lg bg-green-200 dark:bg-green-800 shadow-md text-right cursor-default select-none flex items-center gap-x-2">
                {{ (page.props.flash as any).message }}
                <font-awesome-icon icon="fa-solid fa-x" @click="handleCloseToast"
                    class="cursor-pointer hover:text-black/70 dark:hover:text-gray-300 transition duration-200" />
            </div>
        </div>
    </div>
    <Toaster />
</template>