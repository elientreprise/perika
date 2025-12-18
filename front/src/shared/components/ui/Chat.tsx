
import React from "react";

type Props = {
    fullName: string;
    text: string
    formattedCreatedAt: string;
    translateStatus: string;
    chatEnd?: boolean;
};

export default function Chat({
                                      fullName,
                                      text,
                                      formattedCreatedAt,
                                      translateStatus,
                                      chatEnd = false
                                  }: Props) {
    return (
        <div className={`chat ${chatEnd ? 'chat-end' : 'chat-start'} text-xs`}>
            <div className="chat-header">
                {fullName}
                <time className="text-xs opacity-50">{formattedCreatedAt}</time>
            </div>
            <div className="chat-bubble">{text}</div>
            <div className="chat-footer opacity-50">
                {
                    translateStatus
                }
            </div>
        </div>
    );
}
