<style>
    @media (min-width: 768px) {
        .inside-right-sidebar {
            height: 99%;
        }

        .widget_simple_toc_widget {
            position: sticky;
            top: 89px;
        }

        .widget_simple_toc_widget > ul {
            overflow-y: scroll;
            margin-bottom: 40px;
            max-height: calc(100vh - 250px);
        }

        .widget_simple_toc_widget > ul::-webkit-scrollbar {
            width: 4px;
            background-color: #f7f8f9;
        }

        .widget_simple_toc_widget > ul::-webkit-scrollbar-thumb {
            background-color: #ff0b0b;
        }

        @media (prefers-color-scheme: dark) {
            .widget_simple_toc_widget > ul::-webkit-scrollbar {
                width: 4px;
                background-color: #171716;
            }

            .widget_simple_toc_widget > ul::-webkit-scrollbar-thumb {
                background-color: #af0001;
            }
        }
    }
</style>
