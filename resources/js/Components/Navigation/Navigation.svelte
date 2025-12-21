<script lang="ts">
    // Navigation component for the application
    import {Link, page} from '@inertiajs/svelte'

    const links = [
        {name: "Hooks", href: "/hook-search"},
        {name: "Classes", href: "/class-search"},
        {name: "Documentor", href: "/documentation"},
        {name: "Users", href: "/users"},
        {name: "Account", href: "/account/profile"},
    ]
</script>

<nav>
    <ul class="flex space-x-4 p-4 bg-gray-800 text-white">

        <li class="flex-1">
            <a href="/" class="font-bold text-lg hover:underline">{$page.props.appName}</a>
        </li>

        {#each links as link}
            <li>
                <Link href="{link.href}" class="hover:underline">{link.name}</Link>
            </li>
        {/each}
    </ul>

</nav>
