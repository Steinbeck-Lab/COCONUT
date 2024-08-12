<div class="mx-auto max-w-4xl lg:max-w-7xl px-4 md:px-10">
    <div class="py-10 bg-white mt-32 rounded-lg border">
        <div
            class="mx-auto max-w-3xl px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
            <div class="flex items-center space-x-5">
                <div>
                    <p class="text-secondary-dark text-lg my-0">{{ $molecule->identifier }}</p>
                    <h2
                        class="mb-2 text-2xl break-all font-bold leading-7 break-words text-gray-900 sm:text-3xl sm:tracking-tight">
                        {{ $molecule->name ? $molecule->name : $molecule->iupac_name }}
                    </h2>
                    <p class="text-sm font-medium text-gray-500">Created on <time
                            datetime="{{ $molecule->created_at }}">{{ $molecule->created_at }}</time> &middot; Last
                        update on <time datetime="{{ $molecule->updated_at }}">{{ $molecule->updated_at }}</time></p>
                </div>
            </div>
        </div>
        @if ($molecule->properties)
            <div class="border-b mt-8 border-b-gray-900/10 lg:border-t lg:border-t-gray-900/5">
                <dl class="mx-auto grid max-w-7xl grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 lg:px-2 xl:px-0">
                    <div
                        class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8 ">
                        <dt class="font-medium text-gray-500"> NPLikeness
                            <div class="tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    aria-hidden="true" class="h-5 w-5 -mt-1 inline cursor-pointer">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="tooltiptext">NP Likeness Score: The likelihood of the compound to be a
                                    natural
                                    product, ranges from -5 (less likely) to 5 (very likely).</span>
                            </div>
                        </dt>
                        <div data-v-5784ed69="" class="inline-block"
                            style="border: 30px solid transparent; margin: -30px; --c81fc0a4: 9999;">
                            <div data-v-5784ed69=""><span>
                                    <div class="wrap">
                                        @foreach (range(0, ceil(npScore($molecule->properties->np_likeness))) as $i)
                                            <div></div>
                                        @endforeach
                                    </div>
                                    <span class="ml-1 text-sm font-bold">{{ $molecule->properties->np_likeness }}</span>
                                </span></div>
                        </div>
                    </div>
                    <div
                        class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8 sm:border-l">
                        <div>
                            <dt class="font-medium text-gray-500"> Annotation Level</dt>
                            <div class="flex items-center">
                                @for ($i = 0; $i < $molecule->annotation_level; $i++)
                                    <span class="text-amber-300">★<span>
                                @endfor
                                @for ($i = $molecule->annotation_level; $i < 5; $i++)
                                    ☆
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8 lg:border-l">
                        <div>
                            <dt class="font-medium text-gray-500">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        Mol. Weight
                                    </div>
                                    <div x-data="{ tooltip: false }" x-on:mouseover="tooltip = true"
                                        x-on:mouseleave="tooltip = false" class="ml-2 h-5 w-5 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div x-show="tooltip"
                                            class="text-sm text-white absolute bg-green-400 rounded-lg p-2 transform -translate-y-8 translate-x-8">
                                            Exact Isotopic Mass is calculated using RDKit - <a href="https://www.rdkit.org/docs/source/rdkit.Chem.Descriptors.html">https://www.rdkit.org/docs/source/rdkit.Chem.Descriptors.html</a>
                                        </div>
                                    </div>
                                </div>

                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $molecule->properties->exact_molecular_weight }}
                            </dd>
                        </div>
                    </div>
                    <div
                        class="flex items-baseline flex-wrap justify-between gap-y-2 gap-x-4 border-t border-gray-900/5 px-4 py-10 sm:px-6 lg:border-t-0 xl:px-8 sm:border-l">
                        <div>
                            <dt class="font-medium text-gray-500 text-gray-500"> Mol. Formula </dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $molecule->properties->molecular_formula }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
        @endif
        <div
            class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2 lg:col-start-1">
                @if ($molecule->organisms && count($molecule->organisms) > 0)
                    <section>
                        <div class="bg-white border shadow sm:rounded-lg" x-data="{ showAll: false }">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="applicant-information-title"
                                    class="text-lg font-medium leading-6 text-gray-900">
                                    Organisms ({{ count($molecule->organisms) }})
                                </h2>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="no-scrollbar px-4 py-4 lg:px-8 min-w-0">
                                    <ul role="list" class="mt-2 leading-8">
                                        @foreach ($molecule->organisms as $index => $organism)
                                            @if ($organism != '')
                                                <li class="inline" x-show="showAll || {{ $index }} < 10">
                                                    <span class="isolate inline-flex rounded-md shadow-sm mb-2">
                                                        <a href="/search?type=tags&amp;q={{ urlencode($organism->name) }}&amp;tagType=organisms"
                                                            target="_blank"
                                                            class="relative inline-flex items-center rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 organism">
                                                            {{ $organism->name }}&nbsp;
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m9 9 6-6m0 0 6 6m-6-6v12a6 6 0 0 1-12 0v-3" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ urldecode($organism->iri) }}" target="_blank"
                                                            class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10 capitalize">
                                                            {{ $organism->rank }}&nbsp;
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                            </svg>
                                                        </a>
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    @if(count($molecule->organisms) > 10)
                                    <div class="mt-4">
                                        <button @click="showAll = true" x-show="!showAll"
                                            class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                            View More ↓
                                        </button>
                                        <button @click="showAll = false" x-show="showAll"
                                            class="text-base font-semibold leading-7 text-secondary-dark  text-sm">
                                            View Less ↑
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                @if ($molecule->geo_locations && count($molecule->geo_locations) > 0)
                    <section>
                        <div class="bg-white border shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="applicant-information-title"
                                    class="text-lg font-medium leading-6 text-gray-900">
                                    Geolocations</h2>
                            </div>
                            <div class="border-t border-gray-200">
                                <div class="no-scrollbar px-4 py-4 lg:px-8 min-w-0">
                                    <ul role="list" class="mt-2 leading-8">
                                        @foreach ($molecule->geo_locations as $geo_location)
                                            @if ($geo_location != '')
                                                <li class="inline">
                                                    <a class="text-sm relative mr-2 inline-flex items-center rounded-md border border-gray-300 px-3 py-0.5"
                                                        target="_blank">
                                                        {{ $geo_location->name }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                <section>
                    <div class="bg-white border shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h2 id="applicant-information-title" class="text-lg font-medium leading-6 text-gray-900">
                                Representations</h2>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Molecular details</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <div class="no-scrollbar px-4 lg:px-8 min-w-0">
                                <article>
                                    <div class="">
                                        <section id="representations" class="my-4">
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    COCONUT id
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->identifier }}
                                                </div>
                                            </div>
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    Name
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->name ? $molecule->name : '-' }}
                                                </div>
                                            </div>
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    IUPAC name
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->iupac_name }}
                                                </div>
                                            </div>
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    InChI
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->standard_inchi }}
                                                </div>
                                            </div>
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    InChIKey
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->standard_inchi_key }}
                                                </div>
                                            </div>
                                            <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                <dt
                                                    class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                    Canonical SMILES (RDKit)
                                                </dt>
                                                <div class="mt-1 break-all text-sm text-gray-900">
                                                    {{ $molecule->canonical_smiles }}
                                                </div>
                                            </div>
                                            @if ($molecule->properties)
                                                <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                    <div class="sm:flex sm:justify-between">
                                                        <div class="text-sm font-medium text-gray-500"> Murcko
                                                            Framework
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 break-all text-sm text-gray-900">
                                                        {{ $molecule->properties->murcko_framework ? $molecule->properties->murcko_framework : '-' }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($molecule->synonyms && count($molecule->synonyms) > 0)
                                                <div class="group/item -ml-4 rounded-xl p-4 hover:bg-slate-100">
                                                    <dt
                                                        class="text-sm font-medium text-gray-500 sm:flex sm:justify-between">
                                                        Synonyms
                                                    </dt>

                                                    <div x-data="{ showAll: false }">
                                                        <div class="no-scrollbar min-w-0">
                                                            <ul role="list" class="mt-2 leading-8">
                                                                @foreach ($molecule->synonyms as $index => $synonym)
                                                                    @if ($synonym != '')
                                                                        <li class="inline"
                                                                            x-show="showAll || {{ $index }} < 10">
                                                                            <span
                                                                                class="border px-4 bg-white isolate inline-flex rounded-md shadow-sm mb-2">
                                                                                    {{ $synonym }}
                                                                            </span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                            @if($molecule->synonym_count > 10)
                                                            <div class="mt-4">
                                                                <button @click="showAll = true" x-show="!showAll"
                                                                    class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                                                    View More ↓
                                                                </button>
                                                                <button @click="showAll = false" x-show="showAll"
                                                                    class="text-base font-semibold leading-7 text-secondary-dark  text-sm">
                                                                    View Less ↑
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </section>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </section>

                @if ($molecule->properties)
                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Chemical
                                        classification
                                    </h2>
                                </div>
                                <div class="px-4 py-6 sm:px-6">
                                    <ul role="list" class="px-0">
                                        <li class="py-5 flex md:py-0"><span class="ml-3 text-base text-gray-500">
                                                <b>Super class</b>:
                                                {{ $molecule->properties && $molecule->properties['chemical_super_class'] ? $molecule->properties['chemical_super_class'] : '-' }}
                                            </span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500"><b>Class</b>:
                                                {{ $molecule->properties && $molecule->properties['chemical_class'] ? $molecule->properties['chemical_class'] : '-' }}</span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500"><b>Sub
                                                    class</b>:
                                                {{ $molecule->properties && $molecule->properties['chemical_sub_class'] ? $molecule->properties['chemical_sub_class'] : '-' }}
                                            </span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500"><b>Direct
                                                    parent</b>:
                                                {{ $molecule->properties && $molecule->properties['direct_parent_classification'] ? $molecule->properties['direct_parent_classification'] : '-' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif


                <section aria-labelledby="notes-title">
                    <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                        <div class="divide-y divide-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="notes-title" class="text-lg font-medium text-gray-900">References</h2>
                            </div>
                            <section>
                                <div class="px-4 py-6 sm:px-6">
                                    <h2 id="notes-title" class="mb-2 text-lg font-medium text-gray-900">Citations</h2>
                                    @if (count($molecule->citations) > 0)
                                        <div x-data="{ showAllCitations: false }">
                                            <div class="not-prose grid grid-cols-1 gap-6 sm:grid-cols-2">
                                                @foreach ($molecule->citations as $index => $citation)
                                                    @if ($citation->title != '')
                                                        <div class="group relative rounded-xl border border-slate-200"
                                                            x-show="showAllCitations || {{ $index }} < 6">
                                                            <div
                                                                class="absolute -inset-px rounded-xl border-2 border-transparent opacity-0 [background:linear-gradient(var(--quick-links-hover-bg,theme(colors.sky.50)),var(--quick-links-hover-bg,theme(colors.sky.50)))_padding-box,linear-gradient(to_top,theme(colors.indigo.400),theme(colors.cyan.400),theme(colors.sky.500))_border-box] group-hover:opacity-100">
                                                            </div>
                                                            <div class="relative overflow-hidden rounded-xl p-6">
                                                                <svg aria-hidden="true" viewBox="0 0 32 32"
                                                                    fill="none"
                                                                    class="h-8 w-8 [--icon-foreground:theme(colors.slate.900)] [--icon-background:theme(colors.white)]">
                                                                    <defs>
                                                                        <radialGradient cx="0" cy="0"
                                                                            r="1" gradientUnits="userSpaceOnUse"
                                                                            id=":R1k19n6:-gradient"
                                                                            gradientTransform="matrix(0 21 -21 0 12 11)">
                                                                            <stop stop-color="#0EA5E9"></stop>
                                                                            <stop stop-color="#22D3EE" offset=".527">
                                                                            </stop>
                                                                            <stop stop-color="#818CF8" offset="1">
                                                                            </stop>
                                                                        </radialGradient>
                                                                        <radialGradient cx="0" cy="0"
                                                                            r="1" gradientUnits="userSpaceOnUse"
                                                                            id=":R1k19n6:-gradient-dark"
                                                                            gradientTransform="matrix(0 24.5 -24.5 0 16 5.5)">
                                                                            <stop stop-color="#0EA5E9"></stop>
                                                                            <stop stop-color="#22D3EE" offset=".527">
                                                                            </stop>
                                                                            <stop stop-color="#818CF8" offset="1">
                                                                            </stop>
                                                                        </radialGradient>
                                                                    </defs>
                                                                    <g class="">
                                                                        <circle cx="12" cy="20" r="12"
                                                                            fill="url(#:R1k19n6:-gradient)"></circle>
                                                                        <path
                                                                            d="M27 12.13 19.87 5 13 11.87v14.26l14-14Z"
                                                                            class="fill-[var(--icon-background)] stroke-[color:var(--icon-foreground)]"
                                                                            fill-opacity="0.5" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M3 3h10v22a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V3Z"
                                                                            class="fill-[var(--icon-background)]"
                                                                            fill-opacity="0.5"></path>
                                                                        <path
                                                                            d="M3 9v16a4 4 0 0 0 4 4h2a4 4 0 0 0 4-4V9M3 9V3h10v6M3 9h10M3 15h10M3 21h10"
                                                                            class="stroke-[color:var(--icon-foreground)]"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                        <path
                                                                            d="M29 29V19h-8.5L13 26c0 1.5-2.5 3-5 3h21Z"
                                                                            fill-opacity="0.5"
                                                                            class="fill-[var(--icon-background)] stroke-[color:var(--icon-foreground)]"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"></path>
                                                                    </g>
                                                                    <g class="hidden">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                                            d="M3 2a1 1 0 0 0-1 1v21a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H3Zm16.752 3.293a1 1 0 0 0-1.593.244l-1.045 2A1 1 0 0 0 17 8v13a1 1 0 0 0 1.71.705l7.999-8.045a1 1 0 0 0-.002-1.412l-6.955-6.955ZM26 18a1 1 0 0 0-.707.293l-10 10A1 1 0 0 0 16 30h13a1 1 0 0 0 1-1V19a1 1 0 0 0-1-1h-3ZM5 18a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H5Zm-1-5a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1Zm1-7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z"
                                                                            fill="url(#:R1k19n6:-gradient-dark)">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                <h2 class="mt-2 font-bold text-base text-gray-900">
                                                                    <a target="_blank"
                                                                        href="https://doi.org/{{ $citation->doi }}">
                                                                        <span
                                                                            class="absolute -inset-px rounded-xl"></span>{{ $citation->title }}
                                                                    </a>
                                                                </h2>
                                                                <h2 class="mt-2 font-display text-base text-slate-900">
                                                                    <a target="_blank"
                                                                        href="https://doi.org/{{ $citation->doi }}">
                                                                        <span
                                                                            class="absolute -inset-px rounded-xl"></span>{{ $citation->authors }}
                                                                    </a>
                                                                </h2>
                                                                <h2 class="mt-2 font-display text-base text-slate-900">
                                                                    <a target="_blank"
                                                                        href="https://doi.org/{{ $citation->doi }}">
                                                                        <span
                                                                            class="absolute -inset-px rounded-xl"></span>{{ $citation->doi }}
                                                                    </a>
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                            @if (count($molecule->citations) > 6)
                                                <div class="flex justify-center mt-4">
                                                    <button @click="showAllCitations = true"
                                                        x-show="!showAllCitations"
                                                        class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                                        View More ↓
                                                    </button>
                                                    <button @click="showAllCitations = false"
                                                        x-show="showAllCitations"
                                                        class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                                        View Less ↑
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-gray-400">No citations</p>
                                    @endif

                                    <h2 id="notes-title" class="mb-2 mt-4 text-lg font-medium text-gray-900">
                                        Collections</h2>
                                    @if (count($molecule->collections) > 0)
                                        <div class="not-prose grid grid-cols-1 gap-6 sm:grid-cols-1"
                                            x-data="{ showAllCollections: false }">
                                            @foreach ($molecule->collections as $index => $collection)
                                                <div
                                                    x-show="showAllCollections || {{ $index }} < 6">
                                                    <div class="group relative rounded-xl border border-slate-200">
                                                        <div class="relative overflow-hidden rounded-xl p-6">
                                                            <svg aria-hidden="true" viewBox="0 0 32 32"
                                                                fill="none"
                                                                class="mb-2 h-8 w-8 [--icon-foreground:theme(colors.slate.900)] [--icon-background:theme(colors.white)]">
                                                                <defs>
                                                                    <radialGradient cx="0" cy="0"
                                                                        r="1" gradientUnits="userSpaceOnUse"
                                                                        id=":R1k19n6:-gradient"
                                                                        gradientTransform="matrix(0 21 -21 0 12 11)">
                                                                        <stop stop-color="#0EA5E9"></stop>
                                                                        <stop stop-color="#22D3EE" offset=".527">
                                                                        </stop>
                                                                        <stop stop-color="#818CF8" offset="1">
                                                                        </stop>
                                                                    </radialGradient>
                                                                    <radialGradient cx="0" cy="0"
                                                                        r="1" gradientUnits="userSpaceOnUse"
                                                                        id=":R1k19n6:-gradient-dark"
                                                                        gradientTransform="matrix(0 24.5 -24.5 0 16 5.5)">
                                                                        <stop stop-color="#0EA5E9"></stop>
                                                                        <stop stop-color="#22D3EE" offset=".527">
                                                                        </stop>
                                                                        <stop stop-color="#818CF8" offset="1">
                                                                        </stop>
                                                                    </radialGradient>
                                                                </defs>
                                                                <g class="">
                                                                    <circle cx="12" cy="20" r="12"
                                                                        fill="url(#:R1k19n6:-gradient)"></circle>
                                                                    <path d="M27 12.13 19.87 5 13 11.87v14.26l14-14Z"
                                                                        class="fill-[var(--icon-background)] stroke-[color:var(--icon-foreground)]"
                                                                        fill-opacity="0.5" stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                    </path>
                                                                    <path
                                                                        d="M3 3h10v22a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V3Z"
                                                                        class="fill-[var(--icon-background)]"
                                                                        fill-opacity="0.5"></path>
                                                                    <path
                                                                        d="M3 9v16a4 4 0 0 0 4 4h2a4 4 0 0 0 4-4V9M3 9V3h10v6M3 9h10M3 15h10M3 21h10"
                                                                        class="stroke-[color:var(--icon-foreground)]"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M29 29V19h-8.5L13 26c0 1.5-2.5 3-5 3h21Z"
                                                                        fill-opacity="0.5"
                                                                        class="fill-[var(--icon-background)] stroke-[color:var(--icon-foreground)]"
                                                                        stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </g>
                                                                <g class="hidden">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M3 2a1 1 0 0 0-1 1v21a6 6 0 0 0 12 0V3a1 1 0 0 0-1-1H3Zm16.752 3.293a1 1 0 0 0-1.593.244l-1.045 2A1 1 0 0 0 17 8v13a1 1 0 0 0 1.71.705l7.999-8.045a1 1 0 0 0-.002-1.412l-6.955-6.955ZM26 18a1 1 0 0 0-.707.293l-10 10A1 1 0 0 0 16 30h13a1 1 0 0 0 1-1V19a1 1 0 0 0-1-1h-3ZM5 18a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H5Zm-1-5a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1Zm1-7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z"
                                                                        fill="url(#:R1k19n6:-gradient-dark)"></path>
                                                                </g>
                                                            </svg>
                                                            <a href="/search?type=tags&amp;q={{ $collection->title }}&amp;tagType=dataSource" class="hover:pointer font-bold text-base text-xl text-gray-900">
                                                                {{ $collection->title }} <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 inline">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 6-6m0 0 6 6m-6-6v12a6 6 0 0 1-12 0v-3"></path>
                                                                </svg>
                                                            </a>
                                                            <h2 x-show="$collection->description" class="mt-2 font-display text-base text-slate-900">
                                                                {{ $collection->description }}
                                                            </h2>
                                                            <h2 x-show="$collection->doi" class="mt-2 font-display text-base text-slate-900">
                                                                {{ $collection->doi }}
                                                            </h2>
                                                            <h2  x-show="$collection->pivot->reference" class="hover:text-blue-500 mt-1 font-display text-base text-slate-900">
                                                                Reference: <a href="{{ $collection->pivot->url }}" target="_blank">{{ $collection->pivot->reference }} <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 inline">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                                                </svg></a>
                                                            </h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($molecule->collections) > 6)
                                                <div class="flex justify-center mt-4">
                                                    <button @click="showAllCollections = true"
                                                        x-show="!showAllCollections"
                                                        class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                                        View More ↓
                                                    </button>
                                                    <button @click="showAllCollections = false"
                                                        x-show="showAllCollections"
                                                        class="text-base font-semibold leading-7 text-secondary-dark text-sm">
                                                        View Less ↑
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-gray-400">No collections</p>
                                    @endif
                                </div>
                            </section>

                        </div>
                    </div>
                </section>

                @if ($molecule->related && count($molecule->related) > 0)
                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Tautomers</h2>
                                </div>
                                <div class="px-4 pb-5 sm:px-6">
                                    <div class="mx-auto grid mt-6 gap-5 lg:max-w-none md:grid-cols-3 lg:grid-cols-2">
                                        @foreach ($molecule->related as $tautomer)
                                            <livewire:molecule-card :molecule="$tautomer" lazy/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($molecule->is_parent)
                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Stereochemical
                                        Variants
                                    </h2>
                                </div>
                                <div class="px-4 pb-5 sm:px-6">
                                    <div class="mx-auto grid mt-6 gap-5 lg:max-w-none md:grid-cols-3 lg:grid-cols-2">
                                        @foreach ($molecule->variants as $variant)
                                            <livewire:molecule-card :molecule="$variant" lazy/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($molecule->parent_id != null)
                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Parent (With our stereo definitions)
                                    </h2>
                                </div>
                                <div class="px-4 pb-5 sm:px-6">
                                    <div class="mx-auto grid mt-6 gap-5 lg:max-w-none md:grid-cols-3 lg:grid-cols-2">
                                        <div class="rounded-lg hover:shadow-lg shadow border">
                                            <livewire:molecule-card :molecule="$molecule->parent" lazy/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($molecule->properties)
                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Molecular
                                        Properties
                                    </h2>
                                </div>
                                <div class="px-4 py-6 sm:px-6">
                                    <div>
                                        <ul role="list" class="px-0">
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Total
                                                    atom number : {{ $molecule->properties->total_atom_count }}</span>
                                            </li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Heavy
                                                    atom number :
                                                    {{ $molecule->properties->heavy_atom_count }}</span></li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Aromatic Ring Count :
                                                    {{ $molecule->properties->aromatic_rings_count }}</span></li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Rotatable Bond count :
                                                    {{ $molecule->properties->rotatable_bond_count }}</span></li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Minimal number of rings
                                                    : {{ $molecule->properties->number_of_minimal_rings }}</span></li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Formal Charge :
                                                    {{ $molecule->properties->total_atom_count }}</span></li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Contains Sugar :
                                                    {{ $molecule->properties->contains_sugar ? 'True' : 'False' }}</span>
                                            </li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Contains Ring Sugars :
                                                    {{ $molecule->properties->contains_ring_sugars ? 'True' : 'False' }}</span>
                                            </li>
                                            <li class="py-5 flex md:py-0"><span
                                                    class="ml-3 text-base text-gray-500">Contains Linear Sugars
                                                    :
                                                    {{ $molecule->properties->contains_linear_sugars ? 'True' : 'False' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section aria-labelledby="notes-title">
                        <div class="bg-white shadow border sm:overflow-hidden sm:rounded-lg">
                            <div class="divide-y divide-gray-200">
                                <div class="px-4 py-5 sm:px-6">
                                    <h2 id="notes-title" class="text-lg font-medium text-gray-900">Molecular
                                        Descriptors
                                    </h2>
                                </div>
                                <div class="px-4 py-6 sm:px-6">
                                    <ul role="list" class="">
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">NP-likeness scores :
                                                {{ $molecule->properties->np_likeness }}</span></li>
                                        <li class="py-5 flex md:py-0"><span class="ml-3 text-base text-gray-500">Alogp
                                                :
                                                {{ $molecule->properties->alogp }}</span></li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">TopoPSA :
                                                {{ $molecule->properties->topological_polar_surface_area }}</span></li>
                                        <li class="py-5 flex md:py-0"><span class="ml-3 text-base text-gray-500">Fsp3
                                                :
                                                {{ $molecule->properties->total_atom_count }}</span></li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">Hydrogen
                                                Bond Acceptor Count
                                                : {{ $molecule->properties->hydrogen_bond_acceptors }}</span></li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">Hydrogen
                                                Bond Donor Count :
                                                {{ $molecule->properties->hydrogen_bond_donors }}</span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">Lipinski
                                                Hydrogen Bond
                                                Acceptor Count :
                                                {{ $molecule->properties->hydrogen_bond_acceptors_lipinski }}</span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">Lipinski
                                                Hydrogen Bond Donor
                                                Count :
                                                {{ $molecule->properties->hydrogen_bond_donors_lipinski }}</span>
                                        </li>
                                        <li class="py-5 flex md:py-0"><span
                                                class="ml-3 text-base text-gray-500">Lipinski
                                                RO5 Violations :
                                                {{ $molecule->properties->lipinski_rule_of_five_violations }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

            </div>
            <section aria-labelledby="timeline-title" class="lg:col-span-1 lg:col-start-3">
                <div class="border aspect-h-2 aspect-w-3 overflow-hidden rounded-lg bg-white mb-2">
                    <livewire:molecule-depict2d :height="300" :smiles="$molecule->canonical_smiles" lazy="on-load">
                </div>
                <div class="border aspect-h-2 aspect-w-3 overflow-hidden rounded-lg mb-2">
                    <livewire:molecule-depict3d :height="300" :smiles="$molecule->canonical_smiles" lazy="on-load">
                </div>
                <div class="bg-white px-4 py-1 shadow sm:rounded-lg sm:px-6 border">
                    <div class="mt-2 flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-500">Created at <a href="#"
                                                        class="font-medium text-gray-900"></a></p>
                                                <time
                                                    datetime="{{ $molecule->created_at }}">{{ $molecule->created_at }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <div class="my-2 flex flex-col justify-stretch">
                        <a class="inline-flex right py-2 text-sm font-semibold">View
                            complete history</a>
                    </div>
                </div>

                <dl class="mt-5 flex w-full">
                    <div class="text-center md:text-left">
                        <dd class="mt-1"><a class="text-base font-semibold text-text-dark hover:text-slate-600"
                                href="/dashboard/reports/create?compound_id={{ $molecule->identifier }}&type=report">
                                Report this compound <span aria-hidden="true">→</span></a></dd>
                        <dd class="mt-1"><a class="text-base font-semibold text-text-dark hover:text-slate-600"
                                href="/dashboard/reports/create?compound_id={{ $molecule->identifier }}&type=change">Request
                                changes to this page <span aria-hidden="true">→</span></a></dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
</div>
