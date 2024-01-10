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
            padding-right: 10px;
            padding-left: 20px;
        }

        .widget_simple_toc_widget > ul li {
            list-style-type: disc;
        }

        .widget_simple_toc_widget > ul::-webkit-scrollbar {
            width: 4px;
        }
    }
