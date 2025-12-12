import FlashMessage from "../ui/FlashMessage.tsx";
import {useContext} from "react";
import {FlashContext} from "../../../app/contexts/FlashContext.tsx";

export default function FlashContainer() {
    const { flashes, remove } = useContext(FlashContext);
    return (
        <div className="fixed top-4 right-4 flex flex-col gap-3 z-50">
            {flashes.map((flash) => (
                <FlashMessage
                    key={flash.id}
                    type={flash.type}
                    message={flash.message}
                    onClose={() => remove(flash.id)}
                />
            ))}
        </div>
    );
}