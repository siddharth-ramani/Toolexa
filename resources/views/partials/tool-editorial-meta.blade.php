@isset($editorialMeta)
    <x-editorial.metadata-row :metadata="$editorialMeta" type="tool" :category="$toolMeta['category']" />
@endisset
@isset($toolQuality)
    <p class="tool-quality-intro">{{ $toolQuality['introduction'] }}</p>
@endisset
