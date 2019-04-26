<template>
    <ul>
        <li v-for="navitem in mainNav" v-bind:key="navitem.ID" class="nav-item" :class="{'dropdown': navitem.children.length > 0 }">
            <a :href="navitem.url" :class="'nav-link ' + navitem.classes" :target="navitem.target !== '' ? navitem.target : '_self'" >{{ navitem.title }}</a>
            <div class="dropdown-menu" v-if="navitem.children.length > 0" >
                <li v-for="(child, i) in navitem.children" v-bind:key="i">
                    <a :href="child.url" :class="'dropdown-item ' + child.classes" :target="navitem.target !== '' ? navitem.target : '_self'" >{{ child.title }}</a>
                </li>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {

        props: {
            mainNav: {}
        }

    }
</script>
<style>
    .main-navigation .dropdown:hover .dropdown-menu {
        display: block;
    }
</style>