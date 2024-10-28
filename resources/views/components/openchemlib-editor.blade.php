<div 
    x-data="{ loaded: false, smiles: '' }"
    x-init="(() => {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/openchemlib@8.15.0/dist/openchemlib-full.js';
        script.onload = () => {
            loaded = true;
            window.editor = OCL.StructureEditor.createSVGEditor('structureEditorContainer', 1);

            // Polling for SMILES updates every 500ms
            setInterval(() => {
                const currentSmiles = window.editor.getSmiles();
                if (currentSmiles && currentSmiles !== $data.smiles) {
                    $data.smiles = currentSmiles;
                    const filamentInput = document.getElementById('mountedActionsData.0.structure-smiles');
                    if (filamentInput) {
                        filamentInput.value = currentSmiles;
                        filamentInput.dispatchEvent(new Event('input'));
                    }
                }
            }, 500);
        };
        document.head.appendChild(script);
    })()"
>
    <div class="modal-body">
        <div 
            id="structureEditorContainer" 
            class="border rounded-lg" 
            style="height: 500px; width: 100%; overflow: hidden; position: relative;"
        >
            <template x-if="!loaded">
                <div class="flex items-center justify-center h-full">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                </div>
            </template>
        </div>
    </div>
</div>
