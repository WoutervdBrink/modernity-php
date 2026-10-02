<script lang="ts" setup>
import type { GitHubRepository } from '@/generated/types/App/Data/Repository';

defineProps<{
    repository: GitHubRepository;
}>();
</script>

<template>
    <DetailList>
        <Detail label="ID">{{ repository.id }}</Detail>
        <Detail label="Full name">{{ repository.full_name }}</Detail>
        <Detail label="Created at"><Timestamp :timestamp="repository.created_at" /></Detail>
        <Detail label="Updated at"><Timestamp :timestamp="repository.updated_at" /></Detail>
        <Detail label="Stargazers">{{ repository.stargazers_count }}</Detail>
        <Detail label="PHP Share">
            <span :title="repository.phpShare?.toString()"> {{ repository.phpShare?.toFixed(2) }}% </span>
        </Detail>
    </DetailList>
</template>
