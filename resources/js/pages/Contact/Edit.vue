<script setup lang="ts">
import Label from '@/components/ui/label/Label.vue';
import Input from '@/components/ui/input/Input.vue';

import { Button } from "@/components/ui/button"

import { Link, useForm } from "@inertiajs/vue3";

import { onMounted, ref, watch } from 'vue';

import InputMask from 'primevue/inputmask';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select"

interface Contact {
    id: number;
    name: string;
    phone: string;
    email: string;
    category: string;
    state: string | { id: number, sigla: string };
    city: string;
};

const props = defineProps<{ contact: Contact }>();

const form = useForm({
    name: props.contact.name,
    phone: props.contact.phone,
    email: props.contact.email,
    category: props.contact.category,
    state: typeof props.contact.state === 'object'
        ? { id: props.contact.state.id, sigla: props.contact.state.sigla }
        : { id: 0, sigla: String(props.contact.state) },
    city: props.contact.city,
});

const handleSubmit = () => {
    form.put(route('contacts.update', props.contact.id));
};

const states = ref<Array<{ id: BigInteger, sigla: string }>>([]);
const cities = ref<Array<{ id: BigInteger, nome: string }>>([]);

onMounted(async () => {
    try {
        await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then(response => {
                response.json().then(data => {
                    states.value = data;
                })
            })

    } catch (error) {
        console.error('Error fetching initial data:', error);
    }
});

watch(
    () => form.state?.id,
    async (stateId) => {
        if (!stateId) {
            cities.value = []
            form.city = ""
            return
        }

        try {
            const res = await fetch(
                `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${stateId}/municipios?orderBy=nome`
            )
            const data = await res.json()
            cities.value = data
            form.city = ""
        } catch (err) {
            console.error("Erro ao buscar cidades:", err)
        }
    }
);

</script>

<template>

    <Head title="Editar um contato" />
    <div class="px-5 py-3 md:px-10 md:py-6 xl:px-40 xl:py-12 flex flex-col gap-y-6">
        <div class="flex items-center justify-between">
            <Link :href="route('contacts.index')" class="text-gray-500 hover:text-gray-700">
            <div class="w-6 h-6 flex items-center justify-center rounded-full text-black dark:text-white">
                <font-awesome-icon icon="fa-solid fa-arrow-left" class="fa-xl" />
            </div>
            </Link>
            <h2 class="text-2xl font-bold">Editar um contato</h2>
            <div></div> <!-- Placeholder para alinhamento -->
        </div>
        <form @submit.prevent="handleSubmit" class="flex flex-col gap-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact name"> Name</Label>
                    <Input type="text" placeholder="Insira um nome" v-model="form.name" />
                    <div v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>

                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact phone"> Telefone</Label>
                    <InputMask type="text" class="h-9 border-2 border-gray-200 rounded-md px-3 text-sm"
                        v-model="form.phone" mask="(99) 9 9999-9999" placeholder="Insira um telefone" unstyled />
                    <div v-if="form.errors.phone">{{ form.errors.phone }}</div>
                </div>

                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact email"> E-Mail</Label>
                    <Input type="email" placeholder="Insira seu E-Mail" v-model="form.email" />
                    <div v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>

                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact category"> Categoria</Label>
                    <Select v-model="form.category">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Selecione uma categoria" />
                        </SelectTrigger>
                        <SelectContent class="max-h-48">
                            <SelectItem value="Aluno">Aluno</SelectItem>
                            <SelectItem value="Responsável">Responsável</SelectItem>
                            <SelectItem value="Professor">Professor</SelectItem>
                            <SelectItem value="Funcionário">Funcionário</SelectItem>
                            <SelectItem value="Gestor">Gestor</SelectItem>
                        </SelectContent>
                    </Select>
                    <div v-if="form.errors.category">{{ form.errors.category }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact state"> Estado (UF)</Label>
                    <Select v-model="form.state">
                        <SelectTrigger class="w-full">
                            <SelectValue
                                :placeholder="typeof props.contact.state === 'object' ? props.contact.state.sigla : (props.contact.state || 'Selecione um estado')" />
                        </SelectTrigger>
                        <SelectContent class="max-h-48">
                            <SelectItem v-for="state in states" :key="state.sigla" :value="state">
                                {{ state.sigla }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div v-if="form.errors.state">{{ form.errors.state }}</div>
                </div>

                <div class="w-full flex flex-col gap-y-2">
                    <Label for="Contact city"> Cidade</Label>
                    <Select v-model="form.city" :disabled="!form.state?.id">
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="props.contact.city || 'Selecione um estado'" />
                        </SelectTrigger>
                        <SelectContent class="max-h-48">
                            <SelectItem v-for="city in cities" :key="city.nome" :value="city.nome">
                                {{ city.nome }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <div v-if="form.errors.city">{{ form.errors.city }}</div>
                </div>
            </div>

            <div class="w-full flex justify-end">
                <Button class="w-full md:w-auto" type="submit" :disabled="form.processing"> Atualizar contato</Button>
            </div>
        </form>
    </div>
</template>