import type {TableProps} from "../../types/TableProps.ts";
import type {RenderParams} from "../../types/RenderParams.ts";


export default function Table({
                                  columns,
                                  rows,
                                  data,
                                  onChange,
                                  errors = {},
                                  readonly = false,
                                  footerRows,
                                  className
                              }: Readonly<TableProps>) {

    const getError = (rowKey: string, colKey: string) => {
        const errorKey = `${rowKey}.${colKey}`;
        return errors?.[errorKey];
    };

    const hasError = (rowKey: string, colKey: string) => {
        return !!getError(rowKey, colKey);
    };

    const renderDefaultCell = (params: RenderParams) => {
        const { value, rowKey, colKey, onChange, hasError, readonly } = params;

        if (readonly) {
            return <span className={hasError ? "text-error text-xs font-semibold" : ""}>{value ?? "-"}</span>;
        }

        return (
            <input
                type="number"
                value={value ?? ""}
                onChange={(e) => onChange?.(rowKey, colKey, Number(e.target.value))}
                className={`input input-xs text-center text-xs ${hasError ? "text-error" : "border"}`}
            />
        );
    };

    return (
        <div>
            <table className="table table-xs">
                <thead>
                    <tr>
                        <th className="text-xs"></th>
                        {columns.map((col) => (
                            <th key={col.key} className="text-xs text-center">
                                {col.label}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                {rows.map((row) => (
                    <tr key={row.key} className={row.disabled ? "opacity-40 pointer-events-none" : ""}>
                        <td className="text-xs">{row.label}</td>

                        {columns.map((col) => {
                            const value = data[row.key]?.[col.key];
                            const error = hasError(row.key, col.key);
                            const errorMessage = getError(row.key, col.key);
                            const disabled = row.disabled || col.disabled;

                            const renderParams: RenderParams = {
                                value,
                                rowKey: row.key,
                                colKey: col.key,
                                onChange: disabled ? undefined : onChange,
                                hasError: error,
                                readonly,
                            };

                            return (
                                <td key={col.key} className="text-center">
                                    <div>
                                        {row.render
                                            ? row.render(renderParams)
                                            : col.render
                                                ? col.render(renderParams)
                                                : renderDefaultCell(renderParams)}

                                        {/*{error && errorMessage && (*/}
                                        {/*    <div className="text-error text-xs mt-1">{errorMessage}</div>*/}
                                        {/*)}*/}
                                    </div>
                                </td>
                            );
                        })}
                    </tr>
                ))}
                </tbody>
                {footerRows && footerRows.length > 0 && (
                    <tfoot>
                    {footerRows.map((row) => (
                        <tr key={row.key} className="bg-base-200 font-semibold">
                            <td className="text-xs">{row.label}</td>

                            {columns.map((col) => (
                                <td key={col.key} className="text-center">
                                    {row.render?.({
                                        value: data[row.key]?.[col.key],
                                        rowKey: row.key,
                                        colKey: col.key,
                                        readonly: true,
                                    })}
                                </td>
                            ))}
                        </tr>
                    ))}
                    </tfoot>
                )}
            </table>
        </div>
    );
}