
import {CategorySelect} from "../../../category/components/CategorySelect.tsx";
import {ActivitySelect} from "../../../activity/components/ActivitySelect.tsx";
import {ProjectSelect} from "../../../project/components/ProjectSelect.tsx";
import type {RenderParams} from "../../../../shared/types/RenderParams.ts";

export const columnRenderers = {
    project: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => (
        <ProjectSelect value={value} onChange={(v) => onChange?.(rowKey, colKey, v)} error={hasError} readonly={readonly} />
    ),
    activity: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => (
        <ActivitySelect value={value} onChange={(v) => onChange?.(rowKey, colKey, v)} error={hasError} readonly={readonly} />
    ),
    typeSource: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => <strong>10000</strong>,

    category: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => (
        <CategorySelect value={value} onChange={(v) => onChange?.(rowKey, colKey, v)} error={hasError} readonly={readonly} />
    ),

    total: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => <strong>{value}</strong>,

};
